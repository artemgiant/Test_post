<?php

namespace App\Controller;

use Carbon\Carbon;
use Exception;
use Spatie\Analytics\Period;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Google_Client;
use Google_Service_Analytics;

class GoogleTransitController extends AbstractController
{
    protected  $apiAuth_key = 'VWtyYWluZUZpcnN0T2ZBbGw=';

    protected function initializeAnalytics($key_file)
    {
        // Creates and returns the Analytics Reporting service object.

        // Use the developers console and download your service account
        // credentials in JSON format. Place them in this directory or
        // change the key file location if necessary.


        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName("Google Analytics");
        $client->setAuthConfig($key_file);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google_Service_Analytics($client);

        return $analytics;
    }



    /**
     * Transit request from SkladUSA with user service account key for Google Analytics.
     *
     * Incoming data is json encoded in base64.
     * Get data for the key and view_id from GoogleAnalytics.
     *
     * Return response data to SkladUSA
     *
     * @Route("/transit/analytics", name="transit_analytics")
     */
    public function transitAnalytics(Request $request)
    {
        $authorization_key = $request->headers->get('Authorization');

        if($authorization_key !=$this->apiAuth_key) {
            $error_response = new Response();
            $error_response->headers->set('Content-Type', 'text/plain');
            $error_response->setContent('Unauthorized request');
            $error_response->setStatusCode('401');
            return $error_response;
        }

        $error_response = new Response();
        $error_response->setContent('Invalid Dataset');
        $error_response->setStatusCode('406');
        $error_response->headers->set('Content-Type', 'text/plain');

        if($this->isJson($request->getContent())) {
            try {
                $data = \GuzzleHttp\json_decode($request->getContent());

                if (empty($data->period)) {
                    $period = 7;
                } else {
                    $period = $data->period;
                }

                if (empty($data->view_id)) {
                    return $error_response;
                } else {
                    $ga_view_id = $data->view_id;
                }

                if (empty($data->key)) {
                    return $error_response;
                } else {
                    $key = \GuzzleHttp\json_encode($data->key);
                }

                $key = base64_decode($key);

//                $temp_file = tempnam(sys_get_temp_dir(), 'TMP_');

                $tmp_dir = '../var/cache/tmp/';

                if (!file_exists($tmp_dir)) {
                    mkdir($tmp_dir, 0777);
                }
                $temp_file = tempnam($tmp_dir, 'TMP_');
                file_put_contents($temp_file, $key);

                $analytics = $this->initializeAnalytics($temp_file);

                //Daily Active Users
                $dau_1_day =  $analytics->data_ga->get(
                    'ga:' . $ga_view_id,
                    '1daysAgo',
                    'yesterday',
                    'ga:users'
                );

                $dau_7_days =  $analytics->data_ga->get(
                    'ga:' . $ga_view_id,
                    '7daysAgo',
                    'yesterday',
                    'ga:users'
                );

                $dau_14_days =  $analytics->data_ga->get(
                    'ga:' . $ga_view_id,
                    '14daysAgo',
                    'yesterday',
                    'ga:users'
                );

                $dau_28_days =  $analytics->data_ga->get(
                    'ga:' . $ga_view_id,
                    '28daysAgo',
                    'yesterday',
                    'ga:users'
                );

                //Total visitors for period
                $total_visitors = $analytics->data_ga->get(
                    'ga:' . $ga_view_id,
                    Carbon::now()->subDays($period)->format('Y-m-d'),
                    'yesterday',
                    'ga:users, ga:pageviews', ['dimensions' => 'ga:date'],
                );

                //Top 10 countries
                $top_countries = $analytics->data_ga->get(
                    'ga:' . $ga_view_id,
                    Carbon::now()->subDays($period)->format('Y-m-d'),
                    'yesterday',
                    'ga:sessions', ['dimensions' => 'ga:country', 'sort'=>'-ga:sessions', 'max-results' => 10],
                );

                //Top 10 browsers
                $top_browsers = $analytics->data_ga->get(
                    'ga:' . $ga_view_id,
                    Carbon::now()->subDays($period)->format('Y-m-d'),
                    'yesterday',
                    'ga:sessions', ['dimensions' => 'ga:browser', 'sort'=>'-ga:sessions', 'max-results' => 10],
                );

                //User Types
                $user_types = $analytics->data_ga->get(
                    'ga:' . $ga_view_id,
                    Carbon::now()->subDays($period)->format('Y-m-d'),
                    'yesterday',
                    'ga:sessions', ['dimensions' => 'ga:userType', 'sort'=>'-ga:sessions'],
                );

                //Traffic Sources
                $traffic_sources = $analytics->data_ga->get(
                    'ga:' . $ga_view_id,
                    Carbon::now()->subDays($period)->format('Y-m-d'),
                    'yesterday',
                    'ga:sessions,ga:users,ga:newUsers,ga:pageviews,ga:sessionDuration,ga:exits', ['dimensions' => 'ga:source,ga:medium', 'sort'=>'-ga:sessions', 'max-results' => 25],
                );

                $visitors_labels = [];
                $visitors_count = [];
                $visitors_views = [];

                foreach ($total_visitors['rows'] as $v){
                    $visitors_labels[] = Carbon::parse($v[0])->format('Y-m-d');
                    $visitors_count[] = $v[1];
                    $visitors_views[] = $v[2];
                }

                $country_labels = [];
                $country_data = [];
                foreach ($top_countries['rows'] as $v){
                    $country_labels[] = $v[0];
                    $country_data[] = $v[1];
                }

                $browser_labels = [];
                $browser_data = [];
                foreach ($top_browsers['rows'] as $v){
                    $browser_labels[] = $v[0];
                    $browser_data[] = $v[1];
                }

                $dataset = [];

                $dataset['errors'] =[];

                $dataset['analytics_error'] = false;

                $dataset['dau_1_day'] = $dau_1_day['rows'][0][0];
                $dataset['dau_7_days'] = $dau_7_days['rows'][0][0];
                $dataset['dau_14_days'] = $dau_14_days['rows'][0][0];
                $dataset['dau_28_days'] = $dau_28_days['rows'][0][0];

                $dataset['visitor_labels'] = $visitors_labels;
                $dataset['visitor_count'] = $visitors_count;
                $dataset['visitor_views'] = $visitors_views;

                $dataset['top_country_labels'] = $country_labels;
                $dataset['top_country_data'] = $country_data;

                $dataset['top_browser_labels'] = $browser_labels;
                $dataset['top_browser_data'] = $browser_data;

                $dataset['user_types'] = $user_types['rows'];
                $dataset['traffic_sources'] = $traffic_sources['rows'];

                $response = new Response();
                $response->setContent(json_encode($dataset));
                $error_response->setStatusCode('200');
                $error_response->headers->set('Content-Type', 'text/plain');

                unlink($temp_file);
                return $response;

            } catch (Exception $e) {
                $dataset = [];
//                $dataset['errors'] = ($e->getMessage());

                $dataset['analytics_error'] = true;
                $dataset['dau_1_day'] = 0;
                $dataset['dau_7_days'] = 0;
                $dataset['dau_14_days'] = 0;
                $dataset['dau_28_days'] = 0;
                $dataset['visitor_labels'] = [];
                $dataset['visitor_count'] = [];
                $dataset['visitor_views'] = [];
                $dataset['top_country_labels'] = [];
                $dataset['top_country_data'] = [];
                $dataset['top_browser_labels'] = [];
                $dataset['top_browser_data'] = [];
                $dataset['user_types'] = [];
                $dataset['traffic_sources'] = [];

                $error_response->setContent(json_encode($dataset));
//                $error_response->setContent( $e->getMessage());
                return $error_response;
            }
        } else {
            return $error_response;
        }

    }

    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}
