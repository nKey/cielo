<?php
/**
 * @author		nKey
 * @brief		Classes relacionadas ao webservice da Cielo
 * @package		dso.cielo
 */

/**
 * @brief	Indicador de autorização
 * @details	A seguir estão os códigos de autorização (campo <autorizar>) que serão incluídos na transação. Para Diners, Discover, Elo e Amex o valor será sempre "3", pois estas bandeiras não possuem programa de autenticação.
 * @li	0 - Não autorizar (somente autenticar)
 * @li	1 - Autorizar somente se autenticada
 * @li	2 - Autorizar autenticada e não autenticada
 * @li	3 - Autorizar sem passar por autenticação (somente para crédito) - Também conhecida como "Autorização Direta"
 * @li	4 - Transação Recorrente
 * @ingroup		Cielo
 * @interface	AuthorizationIndicator
 */
interface AuthorizationIndicator {
	/**
	 * Não autorizar (somente autenticar)
	 */
	const AUTHENTICATE = 0;

	/**
	 * Autorizar somente se autenticada
	 */
	const AUTHORIZE_IF_AUTHENTICATED = 1;

	/**
	 * Autorizar autenticada e não autenticada
	 */
	const AUTHORIZE_IF_AUTHENTICATED_OR_NOT = 2;

	/**
	 * Autorizar sem passar por autenticação (somente para crédito) - Também conhecida como "Autorização Direta"
	 */
	const AUTHORIZE_DIRECTLY = 3;

	/**
	 * Transação Recorrente
	 */
	const RECURRING_TRANSACTION = 4;
}