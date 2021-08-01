SELECT usuario_resp_negociacao                                    as negociador,
       count(numero_ocorrencia)                                   as ocorrencias_recebidas,
       sum(valor_venda)                                           as valor_solicitado,
       sum(faturamento)                                           as valor_faturamento,
       sum(perda_financeira)                                      as valor_perda_financeira,
       round((sum(faturamento) / sum(valor_venda)) * 100, 2)      AS eficiencia_percentual,
       round((sum(perda_financeira) / sum(valor_venda)) * 100, 2) AS perda_financeira_percentual
FROM vw_analitic
WHERE PARAM_ANO_SOL
  AND tipo_solicitacao_id IN (2, 4)
  AND situacao_id in (1, 2, 6, 7)
GROUP BY usuario_resp_negociacao