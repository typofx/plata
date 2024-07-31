<link rel="stylesheet" href="style-desktop-team.css">

<section id="AnchorMeetTheTeam">



    <h2 class="title-team"> Meet The Team </h2>
    <div class="tb-members-container">

        <table class="tb-members">

            <tr class="tr-members">

                <td class="space"></td>

                <?php
                // Caminho do arquivo JSON
                $json_file = 'https://www.plata.ie/api/json/team_members.json';

                // Lendo o conteúdo do arquivo JSON
                $json_data = file_get_contents($json_file);

                // Decodificando o JSON
                $data = json_decode($json_data, true);

                // Mapeamento das redes sociais
                $social_media_mapping = [
                    'WhatsApp' => 'teamSocialMedia0',
                    'Instagram' => 'teamSocialMedia1',
                    'Telegram' => 'teamSocialMedia2',
                    'Facebook' => 'teamSocialMedia3',
                    'GitHub' => 'teamSocialMedia4',
                    'Email' => 'teamSocialMedia5',
                    'Twitter' => 'teamSocialMedia6',
                    'LinkedIn' => 'teamSocialMedia7',
                    'Twitch' => 'teamSocialMedia8',
                    'Medium' => 'teamSocialMedia9',
                ];

                // Verificando se a decodificação foi bem-sucedida
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Iterando sobre os membros da equipe
                    foreach ($data['members'] as $member) {
                        echo '<td class="td-tb-members">';
                        echo '<img class="img-members" src="' . $member['profile_picture'] . '" alt="Foto">';
                        echo '<div class="member-roles">' . $member['position'] . '</div>';
                        echo '<div class="member-names">' . $member['name'] . '</div>';
                        echo '<div class="div-icons">';

                        foreach ($member['social_media'] as $social) {
                            $platform = $social[1];
                            $username = $social[2];

                            if ($username !== 'none') {
                                // Define o link e o ícone correspondente
                                switch ($platform) {
                                    case 'WhatsApp':
                                        $url = 'https://wa.me/' . $username;
                                        $icon = 'https://www.plata.ie/images/social-purple-whatsapp.png';
                                        break;
                                    case 'Instagram':
                                        $url = 'https://www.instagram.com/' . $username;
                                        $icon = 'https://www.plata.ie/images/social-purple-instagram.png';
                                        break;
                                    case 'Telegram':
                                        $url = 'https://t.me/' . $username;
                                        $icon = 'https://www.plata.ie/images/social-purple-telegram.png';
                                        break;
                                    case 'Facebook':
                                        $url = 'https://facebook.com/' . $username;
                                        $icon = 'https://www.plata.ie/images/social-purple-facebook.png';
                                        break;
                                    case 'GitHub':
                                        $url = 'https://github.com/' . $username;
                                        $icon = 'https://www.plata.ie/images/social-purple-github.png';
                                        break;
                                    case 'Email':
                                        $url = 'mailto:' . $username;
                                        $icon = 'https://www.plata.ie/images/social-purple-email.png';
                                        break;
                                    case 'Twitter':
                                        $url = 'https://twitter.com/' . $username;
                                        $icon = 'https://www.plata.ie/images/social-purple-twitter.png';
                                        break;
                                    case 'LinkedIn':
                                        $url = 'https://www.linkedin.com/in/' . $username;
                                        $icon = 'https://www.plata.ie/images/social-purple-linkedin.png';
                                        break;
                                    case 'Twitch':
                                        $url = 'https://www.twitch.tv/' . $username;
                                        $icon = 'https://www.plata.ie/images/social-purple-twitch.png';
                                        break;
                                    case 'Medium':
                                        $url = 'https://medium.com/@' . $username;
                                        $icon = 'https://www.plata.ie/images/social-purple-medium.png';
                                        break;
                                    default:
                                        $url = '#';
                                        $icon = '';
                                        break;
                                }

                                if ($icon !== '') {
                                    echo '<a class="link-social-icons align-right" title="' . strtolower($platform) . '" target="_blank" href="' . $url . '">';
                                    echo '<img class="social-icons align-right" src="' . $icon . '">';
                                    echo '</a>';
                                }
                            }
                        }

                        echo '</div>';
                        echo '</td>';
                    }
                } else {
                    echo 'Erro ao decodificar JSON: ' . json_last_error_msg();
                }
                ?>

                <td class="space"></td>
            </tr>

        </table>
    </div>

    <br>

</section>