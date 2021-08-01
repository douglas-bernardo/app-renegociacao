SELECT
    usuario_resp_negociacao as negociadora,
    sum(valor_venda) as valor_solicitado,
    sum(valor_retido) as valor_retido,
    sum(caixa_retencao) as caixa_retencao,
    sum(valor_venda_novo) as valor_revertido,
    sum(caixa_reversao) as caixa_reversao
FROM
    vw_analitic
WHERE PARAM_ANO_SOL
  AND tipo_solicitacao_id IN (2, 4)
  AND situacao_id in (1, 2, 6, 7)
GROUP BY usuario_resp_negociacao