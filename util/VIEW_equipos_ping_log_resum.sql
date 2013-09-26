select 
        `equipos_ping_log`.`ip` AS `ip`,
        `equipos_ping_log`.`host` AS `host`,
        `equipos_ping_log`.`mac` AS `mac`,
        `equipos_ping_log`.`marca` AS `marca`,
        max(`equipos_ping_log`.`fecha`) AS `max(fecha)`,
        cast(substr(`equipos_ping_log`.`ip`, 9, 3) as unsigned) AS `ipnum`
    from
        `equipos_ping_log`
    group by `equipos_ping_log`.`ip`
    order by 6
