<?php
/**
 * @package utmstat
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */
namespace utmstat;

use utmstat\TableMananger;
use WP_REST_Request;

class Main
{
    protected $tableMananger;
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->Init();
    }

    protected function init() : self
    {
        $this->tableMananger = new TableMananger;
        $this->visitReferer();
        $this->showUtmProfile();
        return $this;
    }

    protected function visitReferer() : self
    {
        add_action('template_redirect', function()
        {
            $user_id = get_current_user_id();
            $response_code = http_response_code();
            $domain = get_site_url();
            $domain = str_replace( "https://", "", $domain);
            $domain = str_replace("http://", "",  $domain);
            $domain = str_replace("www.", "", $domain);
            $ip = $_SERVER['REMOTE_ADDR'];
            $referer = $_SERVER['HTTP_REFERER'];

            if ( strpos($referer, $domain) === false && (int)$response_code < 400 && !empty($referer))
            {

                $url = ( empty($_SERVER['HTTPS']) ? 'http' : 'https' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $utm_source = isset($_GET['utm_source']) ? $_GET['utm_source'] : '';
                $utm_medium = isset($_GET['utm_medium']) ?  $_GET['utm_medium'] : '';
                $utm_campaign = isset($_GET['utm_campaign']) ?  $_GET['utm_campaign'] : '';
                date_default_timezone_set('Europe/Moscow');
                $date_referer = date("Y-m-d H:i:s");

                $this->tableMananger->linksTable->Add($user_id, $ip, $url, $utm_source, $utm_medium, $utm_campaign, $referer, $date_referer);
            }

            if ( !empty($user_id) )
               if( !empty($this->tableMananger->linksTable->CheckUserId($ip)) )
                  $this->tableMananger->linksTable->UpdateUserByIp($user_id, $ip);
        });

        return $this;
    }

    protected function showUtmProfile() : self
    {
        add_action( 'show_user_profile', array($this, 'initUtmProfile') );
        add_action( 'edit_user_profile', array($this, 'initUtmProfile') );
        return $this;
    }

    public function initUtmProfile($user)
    {
        $html = '';
        $user_id = get_current_user_id();
        $user_info_per = get_userdata($user_id);

        if( $user_info_per->user_level >= 7 )
        {
            $utm_row = $this->tableMananger->linksTable->GetLastByUser($user->ID);
            $html .= '<h3>Последние UTM метки пользователя</h3>';
            $html .= '<table class="form-table">';

            $html .= '<tr>';
            $html .= '<th><label for="utm_source">UTM Source</label></th>';
            $html .= '<td><input name="utm_source"  type="text" value="' . $utm_row['source'] . '" class="regular-text"  readonly /></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<th><label for="utm_medium">UTM Medium</label></th>';
            $html .= '<td><input name="utm_medium"  type="text" value="' . $utm_row['medium'] . '" class="regular-text"  readonly /></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<th><label for="utm_campaign">UTM Campaign</label></th>';
            $html .= '<td><input name="utm_campaign"  type="text" value="' . $utm_row['campaign'] . '" class="regular-text"  readonly /></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<th><label for="utm_referal">UTM Referal</label></th>';
            $html .= '<td><input name="utm_referal"  type="text" value="' . $utm_row['referal'] . '" class="regular-text"  readonly /></td>';
            $html .= '</tr>';

            $html .= '</table>';
        }

        echo $html;
    }
}
