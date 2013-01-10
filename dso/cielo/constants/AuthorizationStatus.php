<?php
/**
 * @author		nKey
 * @brief		Classes relacionadas ao webservice da Cielo
 * @package		dso.cielo
 */

/**
 * @brief	Códigos de Resposta da Autorização (LR)
 * @details	A seguir estão os códigos de resposta que respondem por 99,9% dos retornos gerados no processo de autorização. Outros códigos podem ser enviados, para estes casos deve-se assumir que eles não são passíveis de retentativa.
 * @li	00 - Transação autorizada
 * @li	01 - Transação referida pelo emissor
 * @li	04 - Cartão com restrição
 * @li	05 - Transação não autorizada
 * @li	06 - Tente novamente
 * @li	07 - Cartão com restrição
 * @li	12 - Transação inválida
 * @li	13 - Valor inválido
 * @li	14 - Cartão inválido
 * @li	15 - Emissor inválido
 * @li	41 - Cartão com restrição
 * @li	51 - Saldo insuficiente
 * @li	54 - Cartão vencido
 * @li	57 - Transação não permitida
 * @li	58 - Transação não permitida
 * @li	62 - Cartão com restrição
 * @li	63 - Cartão com restrição
 * @li	76 - Tente novamente
 * @li	78 - Cartão não foi desbloqueado pelo portador
 * @li	82 - Transação inválida
 * @li	91 - Banco indisponível
 * @li	96 - Tente novamente
 * @li	AA - Tente novamente
 * @li	AC - Cartão de débito tentando utilizar produto crédito
 * @li	GA - Transação referida pela Cielo
 * @li	N7 - Código de segurança inválido (Visa)
 * @ingroup		Cielo
 * @interface	AuthorizationStatus
 */
interface AuthorizationStatus {
	/**
	 * Transação autorizada
	 */
	const TRANSACTION_AUTHORIZED = 00;

	/**
	 * Transação referida pelo emissor
	 * Ação: Oriente o portador a contatar o emissor do cartão
	 */
	const TRANSACTION_DENIED_BY_ISSUER = 01;

	/**
	 * Cartão com restrição
	 * Ação: Oriente o portador a contatar o emissor do cartão
	 */
	const CARD_RESTRICTED_1 = 04;

	/**
	 * Transação não autorizada
	 */
	const TRANSACTION_UNAUTHORIZED = 05;

	/**
	 * Tente novamente
	 */
	const TRY_AGAIN_1 = 06;

	/**
	 * Cartão com restrição
	 * Ação: Oriente o portador a contatar o emissor do cartão
	 */
	const CARD_RESTRICTED_2 = 07;

	/**
	 * Transação inválida
	 */
	const TRANSACTION_INVALID_1 = 12;

	/**
	 * Valor inválido
	 * Ação: Verifique valor mínimo de R$ 5,00 para parcelamento
	 */
	const VALUE_INVALID = 13;

	/**
	 * Cartão inválido
	 */
	const CARD_INVALID = 14;

	/**
	 * Emissor inválido
	 */
	const ISSUER_INVALID = 15;

	/**
	 * Cartão com restrição
	 * Ação: Oriente o portador a contatar o emissor do cartão
	 */
	const CARD_RESTRICTED_3 = 41;

	/**
	 * Saldo insuficiente
	 */
	const NO_FUNDS = 51;

	/**
	 * Cartão vencido
	 */
	const CARD_EXPIRED = 54;

	/**
	 * Transação não permitida
	 */
	const TRANSACTION_NOT_ALLOWED_1 = 57;

	/**
	 * Transação não permitida
	 */
	const TRANSACTION_NOT_ALLOWED_2 = 58;

	/**
	 * Cartão com restrição
	 * Ação: Oriente o portador a contatar o emissor do cartão
	 */
	const CARD_RESTRICTED_4 = 62;

	/**
	 * Cartão com restrição
	 * Ação: Oriente o portador a contatar o emissor do cartão
	 */
	const CARD_RESTRICTED_5 = 63;

	/**
	 * Tente novamente
	 */
	const TRY_AGAIN_2 = 76;

	/**
	 * Cartão não foi desbloqueado pelo portador
	 * Ação: Oriente o portador a desbloquea-lo junto ao emissor do cartão
	 */
	const CARD_BLOCKED = 78;

	/**
	 * Transação inválida
	 */
	const TRANSACTION_INVALID_2 = 82;

	/**
	 * Banco indisponível
	 */
	const BANK_UNAVAILABLE = 91;

	/**
	 * Tente novamente
	 */
	const TRY_AGAIN_3 = 96;

	/**
	 * Tente novamente
	 */
	const TRY_AGAIN_4 = 'AA';

	/**
	 * Cartão de débito tentando utilizar produto crédito
	 */
	const DEBIT_ONLY = 'AC';

	/**
	 * Transação referida pela Cielo
	 * Ação: Aguarde contato da Cielo
	 */
	const TRANSACTION_DENIED_BY_CIELO = 'GA';

	/**
	 * Código de segurança inválido (Visa)
	 */
	const SECURITY_CODE_INVALID = 'N7';
}