SELECT
    n.usuario_id,
    DATE_FORMAT( o.dtocorrencia , '%Y') AS ano_sol,
    u.primeiro_nome AS usuario_resp,
    sum(o.valor_venda) AS valor_solicitado,
    vl_aberto.valor_em_aberto,
    round( (vl_aberto.valor_em_aberto / sum(o.valor_venda)) * 100, 2 ) AS percentual
FROM
    negociacao n
        LEFT JOIN ocorrencia o ON n.ocorrencia_id = o.id
        LEFT JOIN usuario u ON n.usuario_id = u.id
        LEFT JOIN (SELECT
                       n.usuario_id,
                       sum(o.valor_venda) AS valor_em_aberto
                   FROM
                       negociacao n
                           LEFT JOIN ocorrencia o ON n.ocorrencia_id = o.id
                           LEFT JOIN usuario u ON n.usuario_id = u.id
                   WHERE
                         PARAM_ANO_SOL
                     AND n.tipo_solicitacao_id IN (2, 4)
                     AND n.situacao_id = 1
                     AND PARAM_USER_RESP
                   GROUP BY n.usuario_id) AS vl_aberto ON vl_aberto.usuario_id = n.usuario_id
WHERE
      PARAM_ANO_SOL
  AND n.tipo_solicitacao_id IN (2, 4)
  AND n.situacao_id IN (1, 2, 6, 7)
  AND PARAM_USER_RESP
GROUP BY n.usuario_id, ano_sol