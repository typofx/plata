<?php

function get_live_tokenomics_data($conn, $PLTUSD) {

    $circulating_supply = 11299000992;

 
    if (!function_exists('get_dex_base_name_from_string')) {
        function get_dex_base_name_from_string($exchange_name) {
            $known_dexes = ['MM Finance', 'CurveFi', 'SushiSwap', 'QuickSwap', 'Uniswap'];
            foreach ($known_dexes as $dex) {
                if (stripos($exchange_name, $dex) === 0) return $dex;
            }
            return explode(' ', $exchange_name)[0];
        }
    }
    if (!function_exists('get_authoritative_display_name')) {
        function get_authoritative_display_name($group_name_tag, $groups_map_param) {
            if (isset($groups_map_param[$group_name_tag])) {
                return $groups_map_param[$group_name_tag];
            }
            if ($group_name_tag == 'others') return 'Others';
            return ucwords(str_replace('_', ' ', $group_name_tag));
        }
    }



    $sql_wallets = "SELECT walletname, balance, wallet_group, walletAddress FROM granna80_bdlinks.tokenomics WHERE visible = 1";
    $result_wallets = $conn->query($sql_wallets);
    $all_db_wallets = [];
    while ($row = $result_wallets->fetch_assoc()) {
        $plt = $row['balance'] / 10000;
        $all_db_wallets[] = [
            'exchange'      => $row['walletname'],
            'walletAddress' => $row['walletAddress'],
            'group_wallet'  => $row['wallet_group'],
            'liquidity'     => ($PLTUSD * $plt),
            'percentage'    => ($plt / $circulating_supply),
            'plata'         => $plt,
        ];
    }


    $json_url_dex = 'https://typofx.ie/plataforma/panel/lp-contracts/lp_contracts.json';
    $json_data_dex = @file_get_contents($json_url_dex);
    $data_dex = json_decode($json_data_dex, true);
    $dex_individual_pools = [];
    if (is_array($data_dex)) {
        $plt_contract = '0xc298812164bd558268f51cc6e3b8b5daaf0b6341';
        foreach ($data_dex as $item) {
          if (isset($item['visible']) && $item['visible'] === true && !empty($item['exchange'])) {
                $plt_tokens = 0;
                if (isset($item['contract_a']) && strtolower($plt_contract) == strtolower($item['contract_a'])) {
                    $plt_tokens = $item['tokenBalance_A'];
                } elseif (isset($item['contract_b']) && strtolower($plt_contract) == strtolower($item['contract_b'])) {
                    $plt_tokens = $item['tokenBalance_B'];
                }

                if ($plt_tokens > 0) {
                    $dex_individual_pools[] = [
                        'exchange'      => $item['exchange'] . " - " . $item['pair'],
                        'group_wallet'  => 'dex',
                        'liquidity'     => $plt_tokens * $PLTUSD,
                        'percentage'    => $plt_tokens / $circulating_supply,
                        'walletAddress' => $item['contract'],
                        'plata'         => $plt_tokens
                    ];
                }
            }
        }
    }


    $known_items = array_merge($all_db_wallets, $dex_individual_pools);



    $groups_result = $conn->query("SELECT tag, name FROM granna80_bdlinks.finance_tools_groups ORDER BY name ASC");
    $groups_map = [];
    while ($group_row = $groups_result->fetch_assoc()) {
        $groups_map[$group_row['tag']] = $group_row['name'];
    }

    $grouped_individuals = [];
    $group_totals = [];

    foreach ($known_items as $item) {
        $group_key = ($item['group_wallet'] == 'dex') 
            ? get_dex_base_name_from_string($item['exchange']) 
            : get_authoritative_display_name($item['group_wallet'], $groups_map);

        if (!isset($group_totals[$group_key])) {
            $group_totals[$group_key] = ['exchange' => $group_key, 'liquidity' => 0, 'percentage' => 0, 'plata' => 0];
            $grouped_individuals[$group_key] = [];
        }

        $group_totals[$group_key]['liquidity'] += (float)$item['liquidity'];
        $group_totals[$group_key]['percentage'] += (float)$item['percentage'];
        $group_totals[$group_key]['plata'] += (float)$item['plata'];
        $grouped_individuals[$group_key][] = $item;
    }


    
    $total_plata_agrupado = array_sum(array_column($group_totals, 'plata'));
    $remaining_plt = $circulating_supply - $total_plata_agrupado;

  
    $group_totals['Others'] = [
        'exchange'      => 'Others',
        'liquidity'     => $remaining_plt * $PLTUSD,
        'percentage'    => $remaining_plt / $circulating_supply,
        'plata'         => $remaining_plt
    ];

 
    uasort($group_totals, fn($a, $b) => $b['liquidity'] <=> $a['liquidity']);
    

    
    $table_render_data = [];
    foreach ($group_totals as $group_name => $total_data) {
   
        $table_render_data[] = ['is_group' => true, 'data' => $total_data];
        

        if ($group_name === 'Others') {
            continue;
        }

      
        if (isset($grouped_individuals[$group_name])) {
            usort($grouped_individuals[$group_name], fn($a, $b) => $b['liquidity'] <=> $a['liquidity']);
            foreach ($grouped_individuals[$group_name] as $individual_item) {
                $table_render_data[] = ['is_group' => false, 'data' => $individual_item];
            }
        }
    }
    
    return $table_render_data;
}
?>