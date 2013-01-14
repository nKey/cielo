<?php
/**
 * @author		João Batista Neto
 * @brief		Classes relacionadas ao webservice da Cielo
 * @package		dso.cielo
 */

require_once(dirname(__FILE__) . '/constants/CreditCard.php');
require_once(dirname(__FILE__) . '/constants/CieloMode.php');
require_once(dirname(__FILE__) . '/constants/PaymentProduct.php');
require_once(dirname(__FILE__) . '/constants/SecurityCodeIndicator.php');
require_once(dirname(__FILE__) . '/nodes/PaymentMethodNode.php');
require_once(dirname(__FILE__) . '/nodes/OrderDataNode.php');
require_once(dirname(__FILE__) . '/nodes/EcDataNode.php');
require_once(dirname(__FILE__) . '/nodes/CardDataNode.php');
require_once(dirname(__FILE__) . '/request/TransactionRequest.php');
require_once(dirname(__FILE__) . '/request/CancellationRequest.php');
require_once(dirname(__FILE__) . '/request/CaptureRequest.php');
require_once(dirname(__FILE__) . '/request/QueryRequest.php');
require_once(dirname(__FILE__) . '/../http/CURL.php');

/**
 * Builder para criação dos objetos da integração com a Cielo
 * @ingroup		Cielo
 * @class		Cielo
 */
class Cielo {
	/**
	 * @var	HTTPRequest
	 */
	private $httpRequester;

	/**
	 * URL do webservice
	 * @var string
	 */
	private $cieloURL;

	/**
	 * URL de retorno
	 * @var string
	 */
	private $returnURL;

	/**
	 * Código de afiliação do cliente
	 * @var string
	 */
	private $affiliationCode;

	/**
	 * Chave de afiliação do cliente
	 * @var string
	 */
	private $affiliationKey;

	/**
	 * @var	AbstractCieloNode
	 */
	private $transaction;

	/**
	 * @brief	Constroi o builder
	 * @details	Constroi o builder para integração com o webservice da Cielo
	 * @param	integer $mode Define o modo da integração, os valores possíveis são:
	 * @li		<b>CieloMode::DEPLOYMENT</b> Para o ambiente de testes
	 * @li		<b>CieloMode::PRODUCTION</b> Para o ambiente de produção
	 * @param	string $returnURL URL de retorno
	 * @param	string $affiliationCode Código de afiliação da loja
	 * @param	string $affiliationKey	Chave de afiliação
	 * @see		CieloMode::DEPLOYMENT
	 * @see		CieloMode::PRODUCTION
	 * @throws	InvalidArgumentException Se o modo não for um dos especificados acima.
	 * @throws	InvalidArgumentException Se a URL de retorno for inválida.
	 * @throws	InvalidArgumentException Se o código de afiliação for inválido.
	 * @throws	InvalidArgumentException Se a chave de afiliação for inválida.
	 */
	final public function __construct( $mode = CieloMode::PRODUCTION , $returnURL = null , $affiliationCode = null , $affiliationKey = null ) {
		switch ( $mode ) {
			case CieloMode::DEPLOYMENT :
				$this->cieloURL = 'https://qasecommerce.cielo.com.br/servicos/ecommwsec.do';
				break;
			case CieloMode::PRODUCTION :
				$this->cieloURL = 'https://ecommerce.cbmp.com.br/servicos/ecommwsec.do';
				break;
			default :
				throw new InvalidArgumentException( 'Modo inválido' );
		}

		if ( !is_null( $returnURL ) ) {
			$this->setReturnURL( $returnURL );
		}

		if ( !is_null( $affiliationCode ) ) {
			$this->setAffiliationCode( $affiliationCode );
		}

		if ( !is_null( $affiliationKey ) ) {
			$this->setAffiliationKey( $affiliationKey );
		}
	}

	/**
	 * Recupera o XML da última requisição
	 * @param	boolean $highlight Indica se o retorno deverá ser formatado
	 * @return	string
	 * @throws	BadMethodCallException Se nenhuma transação tiver sido efetuada
	 */
	public function __getLastRequest( $highlight = false ) {
		if ( !is_null( $this->transaction ) ) {
			return $this->transaction->getRequestXML( $highlight );
		} else {
			throw new BadMethodCallException( 'Nenhuma transação foi feita ainda' );
		}
	}

	/**
	 * Recupera o XML da última resposta
	 * @param	boolean $highlight Indica se o retorno deverá ser formatado
	 * @return	string
	 * @throws	BadMethodCallException Se nenhuma transação tiver sido efetuada
	 */
	public function __getLastResponse( $highlight = false ) {
		if ( !is_null( $this->transaction ) ) {
			return $this->transaction->getResponseXML( $highlight );
		} else {
			throw new BadMethodCallException( 'Nenhuma transação foi feita ainda' );
		}
	}

	/**
	 * Cria um objeto de requisição de autorização da transacao
	 * @param	string $creditCard Tipo do cartão
	 * @param	string $cardNumber Número do cartão de crédito
	 * @param	integer $cardExpiration Data de expiração do cartão no formato <b>yyyymm</b>
	 * @param	integer $indicator Indicador do código de segurança
 	 * @li	AuthorizationIndicator::UNINFORMED (0 - Não informado)
	 * @li	AuthorizationIndicator::INFORMED (1 - Informado)
	 * @li	AuthorizationIndicator::UNREADABLE (2 - Ilegível)
	 * @li	AuthorizationIndicator::ABSENT (3 - Inexistente)
	 * @param	integer $securityCode Código de segurança do cartão
	 * @param	string $orderNumber Número identificador do pedido
	 * @param	integer $orderValue Valor do pedido
	 * @param	string $paymentProduct Forma de pagamento do pedido, pode ser uma das seguintes:
	 * @li	PaymentProduct::ONE_TIME_PAYMENT - <b>1</b> - Crédito à Vista
	 * @li	PaymentProduct::INSTALLMENTS_BY_AFFILIATED_MERCHANTS - <b>2</b> - Parcelado pela loja
	 * @li	PaymentProduct::INSTALLMENTS_BY_CARD_ISSUERS - <b>3</b> - Parcelado pela administradora
	 * @li	PaymentProduct::DEBIT - <b>A</b> - Débito
	 * @param   integer $parcels Número de parcelas do pedido.
	 * @attention Se $formaPagamento for 1 (Crédito à Vista) ou A (Débito), $parcelas precisa, <b>necessariamente</b>
	 * ser igual a <b>1</b>
	 * @param	string $holderName Nome impresso no cartão
	 * @param	string $orderCurrency Código numérico da moeda na norma ISO 4217 (Real: 986)
	 * @param	string $orderDateTime Data hora do pedido no formato ISO 8601
	 * @param	string $orderLanguage Idioma do pedido: PT (português), EN (inglês) ou ES (espanhol)
	 * @param	string $orderDescription Descrição do pedido
	 * @param	string $orderSoftDescriptor Texto de até 13 caracteres que será exibido na fatura do portador, após o nome do Estabelecimento Comercial
	 * @param	string $freeField Um valor qualquer que poderá ser enviado à Cielo para ser resgatado posteriormente
	 * @return	TransactionRequest
	 */
	final public function buildTransaction( $creditCard , $cardNumber , $cardExpiration , $indicator , $securityCode , $orderNumber , $orderValue , $paymentProduct , $parcels = 1 , $holderName = null , $currency = 986 , $dateTime = null , $language = 'PT' , $description = null , $softDescriptor = null , $freeField = null ) {
		$this->transaction = new TransactionRequest( $this->getHTTPRequester() );
		$this->transaction->addNode( new EcDataNode( $this->getAffiliationCode() , $this->getAffiliationKey() ) );
		$this->transaction->addNode( new CardDataNode( $cardNumber , $cardExpiration , $indicator , $securityCode , $holderName ) );
		$this->transaction->addNode( new OrderDataNode( $orderNumber , $orderValue , $currency , $dateTime , $language , $description , $softDescriptor ) );
		$this->transaction->addNode( new PaymentMethodNode( $paymentProduct , $parcels , $creditCard ) );
		$this->transaction->setReturnURL( $this->returnURL );
		$this->transaction->setURL( $this->cieloURL );

		return $this->transaction;
	}

	/**
	 * @brief	Cria um objeto de requisição de cancelamento de transacao
	 * @details	Constroi o objeto de transação a partir de uma consulta para cancelamento, utilizando o TID (<i>Transaction ID</i>).
	 * @param	string $tid TID da transação que será utilizado para fazer a consulta
	 * @return	CancellationRequest
	 */
	final public function buildCancellationTransaction( $tid ) {
		$this->transaction = new CancellationRequest( $this->getHTTPRequester() );
		$this->transaction->addNode( new EcDataNode( $this->getAffiliationCode() , $this->getAffiliationKey() ) );
		$this->transaction->setTID( $tid );
		$this->transaction->setURL( $this->cieloURL );

		return $this->transaction;
	}

	/**
	 * @brief	Cria um objeto Transacao
	 * @details	Constroi o objeto de transação a partir de uma captura, utilizando o TID (<i>Transaction ID</i>).
	 * @param	string $tid TID da transação que será utilizado para fazer a captura
	 * @param	float $value Valor que será capturado
	 * @return	CaptureRequest
	 * @throws	InvalidArgumentException Se o valor for definido mas não for numérico
	 */
	final public function buildCaptureTransaction( $tid , $value = null ) {
		$nullValue = is_null( $value );

		if ( $nullValue || is_float( $value ) || is_int( $value ) ) {
			$this->transaction = new CaptureRequest( $this->getHTTPRequester() );
			$this->transaction->addNode( new EcDataNode( $this->getAffiliationCode() , $this->getAffiliationKey() ) );
			$this->transaction->setTID( $tid );
			$this->transaction->setURL( $this->cieloURL );

			if ( !$nullValue ) {
				$this->transaction->setValue( $value );
			}

			return $this->transaction;
		} else {
			throw new InvalidArgumentException( sprintf( 'O valor deve ser um inteiro ou float, %s foi dado' , gettype( $value ) ) );
		}
	}

	/**
	 * @brief	Cria um objeto Transacao
	 * @details	Constroi o objeto de transação a partir de uma consulta, utilizando o TID (<i>Transaction ID</i>).
	 * @param	string $tid TID da transação que será utilizado para fazer a consulta
	 * @return	QueryRequest
	 */
	final public function buildQueryTransaction( $tid ) {
		$this->transaction = new QueryRequest( $this->getHTTPRequester() );
		$this->transaction->addNode( new EcDataNode( $this->getAffiliationCode() , $this->getAffiliationKey() ) );
		$this->transaction->setTID( $tid );
		$this->transaction->setURL( $this->cieloURL );

		return $this->transaction;
	}

	/**
	 * Recupera o número de afiliação da loja junto à Cielo
	 * @return	string O código de afiliação
	 */
	public function getAffiliationCode() {
		return $this->affiliationCode;
	}

	/**
	 * Recupera a chave da afiliação da loja junto à Cielo
	 * @return	string A chave de afiliação
	 */
	public function getAffiliationKey() {
		return $this->affiliationKey;
	}

	/**
	 * Recupera o objeto de requisição HTTP
	 * @return	HTTPRequest
	 */
	public function getHTTPRequester() {
		if ( is_null( $this->httpRequester ) ) {
			return new CURL();
		}

		return $this->httpRequester;
	}

	/**
	 * @brief	Recupera a URL de retorno que será utilizado pela Cielo para retornar à loja
	 * @details	O valor retornado pode utilizar o template <b>{pedido}</b> para compor a URL
	 * de retorno, esse valor será substituído pelo número do pedido informado.
	 * @return	string
	 */
	public function getReturnURL() {
		return $this->returnURL;
	}

	/**
	 * Define o código de afiliação
	 * @param	string $affiliationCode Código de afiliação
	 * @throws	InvalidArgumentException Se o código de afiliação não for uma string
	 */
	public function setAffiliationCode( $affiliationCode ) {
		if ( is_string( $affiliationCode ) ) {
			$this->affiliationCode = & $affiliationCode;
		} else {
			throw new InvalidArgumentException( 'Código de afiliação inválido' );
		}
	}

	/**
	 * Define a chave de afiliação
	 * @param	string $affiliationKey Chave de afiliação
	 * @throws	InvalidArgumentException Se a chave de afiliação não for uma string
	 */
	public function setAffiliationKey( $affiliationKey ) {
		if ( is_string( $affiliationKey ) ) {
			$this->affiliationKey = & $affiliationKey;
		} else {
			throw new InvalidArgumentException( 'Chave de afiliação inválida' );
		}
	}

	/**
	 * Define a URL de retorno
	 * @param	string $url
	 * @throws	InvalidArgumentException Se a URL de retorno for inválida
	 */
	public function setReturnURL( $url ) {
		if ( filter_var( $url , FILTER_VALIDATE_URL ) ) {
			$this->returnURL = & $url;
			if ( $this->transaction instanceof TransactionRequest ) {
				$this->transaction->setReturnURL( $url );
			}
		} else {
			throw new InvalidArgumentException( 'URL de retorno inválida' );
		}
	}

	/**
	 * Define o objeto de requisição HTTP
	 * @param	HTTPRequest $httpRequester
	 * @return	CieloBuilder
	 */
	public function useHttpRequester( HTTPRequest $httpRequester ) {
		$this->httpRequester = $httpRequester;

		return $this;
	}
}