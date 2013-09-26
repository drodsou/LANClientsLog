select 
        cast(substr(`t`.`ip`, 9, 3) as unsigned) AS `ipnum`,
        `t`.`ip` AS `t_ip`,
        `e`.`Ip` AS `e_ip`,
        `e`.`NombrePC` AS `NombrePC`,
        `e`.`Host` AS `Host`,
        `u`.`NomeEApe` AS `NomeEApe`
    from
        (((`equipos_todas_ips` `t`
        left join `equipos_ping_log_resumen` `p` ON ((`t`.`ip` = `p`.`ip`)))
        left join `equipos` `e` ON ((`t`.`ip` = `e`.`Ip`)))
        left join `usuarios` `u` ON ((`e`.`usuario_id` = `u`.`id`)))
    where
        (isnull(`p`.`ip`)
            and (cast(substr(`t`.`ip`, 9, 3) as unsigned) > 49))
    order by 3 , 1
