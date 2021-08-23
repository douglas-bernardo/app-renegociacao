SELECT ano_sol,
       usuario_resp_negociacao                                    as negociador,
       count(numero_ocorrencia)                                   as ocorrencias_recebidas,
       sum(valor_venda)                                           as valor_solicitado,
       sum(faturamento)                                           as valor_faturamento,
       sum(perda_financeira)                                      as valor_perda_financeira,
       round((sum(faturamento) / sum(valor_venda)) * 100, 2)      AS eficiencia_percentual,
       round((sum(perda_financeira) / sum(valor_venda)) * 100, 2) AS perda_financeira_percentual,
       goal.target                                                as meta
FROM vw_analitic
         LEFT JOIN (SELECT g.id,
                           g.goal_type_id,
                           g.current_year,
                           g.active,
                           gm.target,
                           gm.month_number
                    FROM goal g
                             LEFT JOIN
                         goal_type gt ON g.goal_type_id = gt.id
                             LEFT JOIN
                         goal_month gm ON gm.goal_id = g.id
                    WHERE gm.month_number = date_format(now(), '%c')
                      AND PARAM_ANO_META) AS goal ON goal.current_year = vw_analitic.ano_sol
WHERE PARAM_ANO_SOLICITACAO
  AND tipo_solicitacao_id IN (2, 4)
  AND situacao_id in (1, 2, 6, 7)
GROUP BY usuario_resp_negociacao, ano_sol