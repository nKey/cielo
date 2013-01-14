<?php
/**
 * @author      João Batista Neto
 * @brief       Classes relacionadas ao webservice da Cielo
 * @package     dso.cielo.nodes
 */

require_once(dirname(__FILE__) . '/TransactionNode.php');
require_once(dirname(__FILE__) . '/../constants/AuthorizationStatus.php');

/**
 * @brief       Nó autorizacao
 * @details     Nó com dados da autorização caso tenha passado por essa etapa.
 * @ingroup     Cielo
 * @class       AuthorizationNode
 */
class AuthorizationNode extends TransactionNode {
    /**
     * @attention Quando negada, é o motivo da negação.
     * @brief   Retorno da autorização.
     * @var     integer
     */
    private $lr;

    /**
     * Código da autorização caso a transação tenha sido autorizada com sucesso.
     * @var     string
     */
    private $arp;

    /**
     * Constroi o objeto que representa o nó autenticacao
     * @param   integer $code Código do processamento.
     * @param   string $message Detalhe do processamento.
     * @param   string $dateTime Data hora do processamento.
     * @param   integer $value Valor do processamento sem pontuação.
     * @attention <b>Os dois últimos dígitos são os centavos.</b>
     * @param   integer $lr Retorno da autorização.
     * @attention Quando negada, é o motivo da negação.
     * @param   string $arp Código da autorização caso a transação tenha sido autorizada com sucesso.
     */
    public function __construct( $code , $message , $dateTime , $value , $lr , $arp ) {
        parent::__construct( $code , $message , $dateTime , $value );
        $this->lr = $lr;
        $this->arp = $arp;
    }

    /**
     * Cria o nó XML referente ao objeto.
     * @return  string
     * @see     XMLNode::createXMLNode()
     */
    public function createXMLNode() {
        $node = '<autorizacao />';

        return $node;
    }

    /**
     * Recupera o retorno da autorização.
     * @attention Quando negada, é o motivo da negação.
     * @return  integer
     */
    public function getLR() {
        return $this->lr;
    }

    /**
     * Código da autorização caso a transação tenha sido autorizada com sucesso.
     * @return  string
     */
    public function getArp() {
        return $this->arp;
    }

    /**
     * Define o código HTTP a partir do retorno da autorização (LR)
     * @return  integer
     */
    public function getHTTPStatusCode() {
        switch ($this->lr) {
            // Success
            case AuthorizationStatus::TRANSACTION_AUTHORIZED:
                return 200; // OK
            // Client error (transaction can be repeated)
            case AuthorizationStatus::BANK_UNAVAILABLE:
            case AuthorizationStatus::CARD_BLOCKED:
            case AuthorizationStatus::NO_FUNDS:
            case AuthorizationStatus::TRY_AGAIN_1:
            case AuthorizationStatus::TRY_AGAIN_2:
            case AuthorizationStatus::TRY_AGAIN_3:
            case AuthorizationStatus::TRY_AGAIN_4:
                return 402; // Payment Required
            // Error (transaction should not be repeated)
            case AuthorizationStatus::CARD_EXPIRED:
            case AuthorizationStatus::CARD_INVALID:
            case AuthorizationStatus::ISSUER_INVALID:
            case AuthorizationStatus::VALUE_INVALID:
            case AuthorizationStatus::SECURITY_CODE_INVALID:
            case AuthorizationStatus::CARD_RESTRICTED_1:
            case AuthorizationStatus::CARD_RESTRICTED_2:
            case AuthorizationStatus::CARD_RESTRICTED_3:
            case AuthorizationStatus::CARD_RESTRICTED_4:
            case AuthorizationStatus::CARD_RESTRICTED_5:
            case AuthorizationStatus::TRANSACTION_INVALID_1:
            case AuthorizationStatus::TRANSACTION_INVALID_2:
            case AuthorizationStatus::TRANSACTION_NOT_ALLOWED_1:
            case AuthorizationStatus::TRANSACTION_NOT_ALLOWED_2:
            case AuthorizationStatus::TRANSACTION_UNAUTHORIZED:
            case AuthorizationStatus::TRANSACTION_DENIED_BY_ISSUER:
            case AuthorizationStatus::TRANSACTION_DENIED_BY_CIELO:
            case AuthorizationStatus::DEBIT_ONLY:
                return 403; // Forbidden
            // Server error (unknown status code)
            default:
                return 502; // Bad Gateway
        }
    }
}