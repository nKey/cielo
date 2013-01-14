<?php
/**
 * @author		João Batista Neto
 * @brief		Classes relacionadas ao webservice da Cielo
 * @package		dso.cielo
 */

/**
 * @brief	Status da Transação
 * @details	O status é a informação base para a loja controlar a transação. Seus possíveis valores são:
 * @li	0 - Criada
 * @li	1 - Em andamento
 * @li	2 - Autenticada
 * @li 10 - Autenticando
 * @li	3 - Não autenticada
 * @li	4 - Autorizada ou pendente de captura
 * @li	5 - Não autorizada
 * @li	6 - Capturada
 * @li 12 - Cancelando
 * @li	9 - Cancelada
 * @li -1 - Desconhecido
 * @ingroup		Cielo
 * @interface	TransactionStatus
 */
interface TransactionStatus {
	/**
	 * Transação criada
	 */
	const CREATED = 0;

	/**
	 * Transação em andamento
	 */
	const ONGOING = 1;

	/**
	 * Transação em fase de autenticação
	 */
	const AUTHENTICATING = 10;

	/**
	 * Transação autenticada
	 */
	const AUTHENTICATED = 2;

	/**
	 * Transação não autenticada
	 */
	const UNAUTHENTICATED = 3;

	/**
	 * Transação autorizada ou pendente de captura
	 */
	const AUTHORIZED = 4;

	/**
	 * Transação não autorizada
	 */
	const UNAUTHORIZED = 5;

	/**
	 * Transação já capturada
	 */
	const CAPTURED = 6;

	/**
	 * Transação em fase de cancelament
	 */
	const CANCELLING = 12;

	/**
	 * Transação cancelada
	 */
	const CANCELLED = 9;

	/**
	 * Status desconhecido
	 */
	const UNKNOWN = -1;
}