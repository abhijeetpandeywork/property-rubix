<?php
/**
 * PropertyRubix — API Location Controller
 */

class ApiLocationController extends ApiBaseController {

    /**
     * GET /api/v1/locations
     * Return hierarchical location tree: Countries -> States -> Cities -> Localities.
     */
    public function index(array $params = []): void {
        $this->authenticate('listings:read');
        $pdo = db();

        // Fetch all active countries
        $countries = $pdo->query("SELECT id, name, slug, flag_icon FROM countries WHERE status='active' ORDER BY sort_order ASC, name ASC")->fetchAll(PDO::FETCH_ASSOC);

        // Fetch all states
        $states = $pdo->query("SELECT id, country_id, name, slug FROM states ORDER BY sort_order ASC, name ASC")->fetchAll(PDO::FETCH_ASSOC);

        // Fetch all active cities
        $cities = $pdo->query("SELECT id, state_id, name, slug, banner_image FROM cities WHERE status='active' ORDER BY sort_order ASC, name ASC")->fetchAll(PDO::FETCH_ASSOC);

        // Fetch all active localities
        $localities = $pdo->query("SELECT id, city_id, name, slug FROM localities WHERE status='active' ORDER BY sort_order ASC, name ASC")->fetchAll(PDO::FETCH_ASSOC);

        // Index children lists
        $statesByCountry = [];
        foreach ($states as $s) {
            $s['id'] = (int)$s['id'];
            $s['country_id'] = (int)$s['country_id'];
            $statesByCountry[$s['country_id']][] = $s;
        }

        $citiesByState = [];
        foreach ($cities as $c) {
            $c['id'] = (int)$c['id'];
            $c['state_id'] = (int)$c['state_id'];
            $citiesByState[$c['state_id']][] = $c;
        }

        $localitiesByCity = [];
        foreach ($localities as $l) {
            $l['id'] = (int)$l['id'];
            $l['city_id'] = (int)$l['city_id'];
            $localitiesByCity[$l['city_id']][] = $l;
        }

        // Build hierarchical tree
        $tree = [];
        foreach ($countries as $country) {
            $cId = (int)$country['id'];
            $country['id'] = $cId;
            $country['states'] = [];

            if (isset($statesByCountry[$cId])) {
                foreach ($statesByCountry[$cId] as $state) {
                    $sId = $state['id'];
                    $state['cities'] = [];

                    if (isset($citiesByState[$sId])) {
                        foreach ($citiesByState[$sId] as $city) {
                            $ctId = $city['id'];
                            $city['localities'] = $localitiesByCity[$ctId] ?? [];
                            $state['cities'][] = $city;
                        }
                    }
                    $country['states'][] = $state;
                }
            }
            $tree[] = $country;
        }

        $this->apiSuccess($tree);
    }
}
