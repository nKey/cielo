<?php
/**
 * @author		nKey
 * @brief		Classes relacionadas ao webservice da Cielo
 * @package		dso.cielo
 */

/**
 * @brief	Indicador sobre o envio do Código de segurança
 * @li	0 - Não informado
 * @li	1 - Informado
 * @li	2 - Ilegível
 * @li	3 - Inexistente
 * @ingroup		Cielo
 * @interface	SecurityCodeIndicator
 */
interface SecurityCodeIndicator {
	/**
	 * Não informado
	 */
	const UNINFORMED = 0;

	/**
	 * Informado
	 */
	const INFORMED = 1;

	/**
	 * Ilegível
	 */
	const UNREADABLE = 2;

	/**
	 * Inexistente
	 */
	const ABSENT = 3;
}