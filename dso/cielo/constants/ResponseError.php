<?php
/**
 * @author      nKey
 * @brief       Classes relacionadas ao webservice da Cielo
 * @package     dso.cielo
 */

/**
 * @brief   Catálogo de Erros
 * @details Os erros que podem ser apresentados na mensagem XML, através da TAG <erro>, estão dispostos a seguir:
 * @li  001 - Mensagem inválida
 * @li  002 - Credenciais inválidas
 * @li  003 - Transação inexistente
 * @li  010 - Inconsistência no envio do cartão
 * @li  011 - Modalidade não habilitada
 * @li  012 - Número de parcelas inválido
 * @li  013 - Flag de autorização automática inválida
 * @li  014 - Autorização Direta inválida
 * @li  015 - Autorização Direta sem Cartão
 * @li  016 - Identificador, TID, inválido
 * @li  017 - Código de segurança ausente
 * @li  018 - Indicador de código de segurança inconsistente
 * @li  019 - URL de Retorno não fornecida
 * @li  020 - Status não permite autorização
 * @li  021 - Prazo de autorização vencido
 * @li  025 - Encaminhamento a autorização não permitido
 * @li  030 - Status inválido para captura
 * @li  031 - Prazo de captura vencido
 * @li  032 - Valor de captura inválido
 * @li  033 - Falha ao capturar
 * @li  040 - Prazo de cancelamento vencido
 * @li  041 - Status não permite cancelamento
 * @li  042 - Falha ao cancelar
 * @li  043 - Valor de cancelamento é maior que valor autorizado
 * @li  053 - Recorrência não habilitada
 * @li  097 - Sistema indisponível
 * @li  098 - Timeout
 * @li  099 - Erro inesperado
 * @interface   AuthorizationIndicator
 */
interface ResponseError {
    /**
     * Erro: Mensagem inválida
     * Descrição: A mensagem XML está fora do formato especificado pelo arquivo ecommerce.xsd
     * Ação: Revisar as informações enviadas na mensagem XML frente às especificações
     */
    const MESSAGE_INVALID = 1;

    /**
     * Erro: Credenciais inválidas
     * Descrição: Impossibilidade de autenticar uma requisição da loja virtual.
     * Ação: Verificar se o número de credenciamento e a chave estão corretos
     */
    const CREDENTIALS_INVALID = 2;

    /**
     * Erro: Transação inexistente
     * Descrição: Não existe transação para o identificador informado
     * Ação: Rever a aplicação
     */
    const TRANSACTION_NOT_FOUND = 3;

    /**
     * Erro: Inconsistência no envio do cartão
     * Descrição: A transação, com ou sem cartão, está divergente com a permissão de envio dessa informação
     * Ação: Rever se o cadastro da loja permite o envio do cartão ou não
     */
    const CARD_DATA_INCOMPATIBLE = 10;

    /**
     * Erro: Modalidade não habilitada
     * Descrição: A transação está configurada com uma modalidade de pagamento não habilitada para a loja
     * Ação: Rever a modalidade de pagamento solicitada
     */
    const PAYMENT_METHOD_INCOMPATIBLE = 11;

    /**
     * Erro: Número de parcelas inválido
     * Descrição: O número de parcelas solicitado ultrapassa o máximo permitido
     * Ação: Rever a forma de pagamento
     */
    const INSTALLMENTS_INVALID = 12;

    /**
     * Erro: Flag de autorização automática inválida
     * Descrição: Flag de autorização automática incompatível com a forma de pagamento solicitada
     * Ação: Rever as regras de utilização da flag <autorizar/>
     */
    const AUTHORIZATION_INDICATOR_INVALID = 13;

    /**
     * Erro: Autorização Direta inválida
     * Descrição: A solicitação de Autorização Direta está inválida
     * Ação: Rever as regras de utilização da Autorização Direta
     */
    const DIRECT_AUTHORIZATION_INVALID = 14;

    /**
     * Erro: Autorização Direta sem Cartão
     * Descrição: A solicitação de Autorização Direta está sem cartão
     * Ação: Rever as regras de utilização da Autorização Direta
     */
    const DIRECT_AUTHORIZATION_WITHOUT_CARD = 15;

    /**
     * Erro: Identificador, TID, inválido
     * Descrição: O TID fornecido está duplicado
     * Ação: Rever a aplicação
     */
    const TRANSACTION_ID_INVALID = 16;

    /**
     * Erro: Código de segurança ausente
     * Descrição: O código de segurança do cartão não foi enviado (essa informação é sempre obrigatória para Amex)
     * Ação: Rever a aplicação
     */
    const SECURITY_CODE_EMPTY = 17;

    /**
     * Erro: Indicador de código de segurança inconsistente
     * Descrição: Uso incorreto do indicador de código de segurança
     * Ação: Revisar as informações de cartão enviadas na mensagem XML
     */
    const SECURITY_CARD_INDICATOR_INCOMPATIBLE = 18;

    /**
     * Erro: URL de Retorno não fornecida
     * Descrição: A URL de Retorno é obrigatória, exceto para recorrência e autorização direta.
     * Ação: Revisar as informações enviadas na mensagem XML
     */
    const RETURN_URL_EMPTY = 19;

    /**
     * Erro: Status não permite autorização
     * Descrição: Não é permitido realizar autorização para o status da transação
     * Ação: Rever as regras de autorização
     */
    const AUTHORIZATION_NOT_ALLOWED = 20;

    /**
     * Erro: Prazo de autorização vencido
     * Descrição: Não é permitido realizar autorização, pois o prazo está vencido
     * Ação: Rever as regras de autorização
     */
    const AUTHORIZATION_EXPIRED = 21;

    /**
     * Erro: Encaminhamento a autorização não permitido
     * Descrição: O resultado da Autenticação da transação não permite a solicitação de Autorização
     * Ação: Rever as regras de autorização
     */
    const AUTHORIZATION_FORWARD_NOT_ALLOWED = 25;

    /**
     * Erro: Status inválido para captura
     * Descrição: O status da transação não permite captura
     * Ação: Rever as regras de captura
     */
    const CAPTURE_NOT_ALLOWED = 30;

    /**
     * Erro: Prazo de captura vencido
     * Descrição: A captura não pode ser realizada, pois o prazo para captura está vencido
     * Ação: Rever as regras de captura
     */
    const CAPTURE_EXPIRED = 31;

    /**
     * Erro: Valor de captura inválido
     * Descrição: O valor solicitado para captura não é válido
     * Ação: Rever as regras de captura
     */
    const CAPTURE_AMOUNT_INVALID = 32;

    /**
     * Erro: Falha ao capturar
     * Descrição: Não foi possível realizar a captura
     * Ação: Realizar nova tentativa. Persistindo, entrar em contato com o Suporte e-commerce e informar o TID da transação.
     */
    const CAPTURE_FAILED = 33;

    /**
     * Erro: Prazo de cancelamento vencido
     * Descrição: O cancelamento não pode ser realizado, pois o prazo está vencido
     * Ação: Rever as regras de cancelamento
     */
    const CANCELLATION_EXPIRED = 40;

    /**
     * Erro: Status não permite cancelamento
     * Descrição: O atual status da transação não permite cancelamento
     * Ação: Rever as regras de cancelamento
     */
    const CANCELLATION_NOT_ALLOWED = 41;

    /**
     * Erro: Falha ao cancelar
     * Descrição: Não foi possível realizar o cancelamento
     * Ação: Realizar nova tentativa. Persistindo, entrar em contato com o Suporte e-commerce e informar o TID da transação.
     */
    const CANCELLATION_FAILED = 42;

    /**
     * Erro: Valor de cancelamento é maior que valor autorizado.
     * Descrição: O valor que está tentando cancelar supera o valor total capturado da transação.
     * Ação: Revisar o valor do cancelamento parcial, pois não pode ser maior que o valor capturado da transação.
     */
    const CANCELLATION_AMOUNT_INVALID = 43;

    /**
     * Erro: Recorrência não habilitada
     * Descrição: O cadastro do lojista não permite o envio de transações recorrentes.
     * Ação: Entre em contato com suporte para saber como habilitar a recorrência no cadastro.
     */
    const RECURRENCE_DISABLED = 53;

    /**
     * Erro: Sistema indisponível
     * Descrição: Falha no sistema
     * Ação: Persistindo, entrar em contato com o Suporte.
     */
    const SERVICE_UNAVAILABLE = 97;

    /**
     * Erro: Timeout
     * Descrição: A aplicação não respondeu dentro de 25 segundos
     * Ação: Persistindo, entrar em contato com o Suporte.
     */
    const SERVICE_TIMEOUT = 98;

    /**
     * Erro: Erro inesperado
     * Descrição: Falha no sistema
     * Ação: Persistindo, entrar em contato com o Suporte e informar o TID da transação.
     */
    const SERVICE_ERROR = 99;
}