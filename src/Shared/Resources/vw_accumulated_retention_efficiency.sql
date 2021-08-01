SELECT usuario_resp_negociacao                                as negociadora,
       count(numero_ocorrencia)                               as ocorrencias_recebidas,
       sum(valor_venda)                                       as valor_solicitado,
       sum(valor_retido)                                      as valor_retido,
       round((sum(valor_retido) / sum(valor_venda)) * 100, 2) AS eficiencia_retencao_percentual
FROM vw_analitic
WHERE PARAM_ANO_SOL
  AND tipo_solicitacao_id IN (2, 4)
  AND situacao_id in (1, 2, 6, 7)
GROUP BY usuario_resp_negociacao