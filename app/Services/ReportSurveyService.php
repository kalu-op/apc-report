<?php
namespace App\Services;

class ReportSurveyService {

    private $survey = [
        [
          'slug' => 'profile',
          'label' => 'Profil de l\'environnement du rendez-vous',
          'type' => 'checkbox',
          'choices' => [
            [ 'label' => 'Agence', 'value' => 'agency' ],
            [ 'label' => 'Cabinet', 'value' => 'office' ],
            [ 'label' => 'Bien', 'value' => 'good' ],
            [ 'label' => 'Moyen', 'value' => 'medium' ],
            [ 'label' => 'Passable', 'value' => 'passable' ],
          ],
          'default' => [],
        ],
        [
          'slug' => 'reception',
          'label' => 'Qualité de la réception',
          'type' => 'rate',
          'min' => 1,
          'max' => 5,
          'default' => 1,
        ],
        [
          'slug' => 'subject',
          'label' => 'Nature du rendez-vous',
          'type' => 'radio',
          'choices' => [
            [ 'label' => 'Nouveau partenaire', 'value' => 'new_partner' ],
            [ 'label' => 'Visite de routine', 'value' => 'routine_visit' ],
          ],
          'default' => 'routine_visit',
        ],
        [
          'slug' => 'has_described_society',
          'label' => 'Présentation de la société avec avantages APC (argumentaire)',
          'type' => 'radio',
          'choices' => [
            [ 'label' => 'Oui', 'value' => true ],
            [ 'label' => 'Non', 'value'=> false ],
          ],
          'default' => false,
        ],
        [
          'slug'  => 'has_showed_extranet',
          'label' => 'Démo Extranet courtage',
          'type' => 'radio',
          'choices' => [
            [ 'label' => 'Oui', 'value' => true ],
            [ 'label' => 'Non', 'value' => false ],
          ],
          'default' => false,
        ],
        [
          'slug' => 'has_showed_automotive_pricing',
          'label' => 'Exemple de tarification auto',
          'type' => 'radio',
          'choices' => [
            [ 'label' => 'Oui', 'value' => true ],
            [ 'label' => 'Non', 'value' => false ],
          ],
          'default' => false,
        ],
        [
          'slug' => 'has_distributed_visual_support',
          'label' => 'Support visuel distribué (plaquette)',
          'type' => 'radio',
          'choices' => [
            [ 'label' => 'Oui', 'value' => true ],
            [ 'label' => 'Non', 'value' => false ],
          ],
          'default' => false,
        ],
        [
          'slug' => 'appointment_quality',
          'label' => 'Qualité du rendez-vous',
          'type' => 'rate',
          'min' => 1,
          'max' => 5,
          'default' => 1,
        ],
        [
          'slug' => 'products_evoked',
          'label' => 'Production future évoquée',
          'type' => 'checkbox',
          'choices' => [
            [ 'label' => 'Auto', 'value' => 'auto' ],
            [ 'label' => 'Moto', 'value' => 'moto' ],
            [ 'label' => 'Habitation', 'value' => 'health' ],
            [ 'label' => 'Protection juridique', 'value' => 'juridic' ],
            [ 'label' => 'Accident de la vie', 'value' => 'life' ],
          ],
          'default' => [],
        ],
        [
          'slug' => 'monthly_volume',
          'label' => 'Volumétrie attendue mensuelle',
          'type' => 'radio',
          'choices' => [
            [ 'label' => 'de 0 à 5 contrats', 'value' => 1 ],
            [ 'label' => 'de 5 à 15 contrats', 'value' => 2 ],
            [ 'label' => 'de 15 à 30 contrats', 'value' => 3 ],
            [ 'label' => 'plus de 30 contrats', 'value' => 4 ],
          ],
          'default' => 1,
        ],
    ];

    private function generateDefaultResponses() {
        $defaultResponses = [];

        foreach ($this->survey as $question) {
            $defaultResponses[$question['slug']] = $question['default'];
        }

        return $defaultResponses;
    }

    public function getSurvey() {
        return $this->survey;
    }

    public function getSurveyStringify() {
        return json_encode($this->survey);
    }

    public function getDefaultResponses() {
        return $this->generateDefaultResponses();
    }

    public function getDefaultResponsesStringify() {
        return json_encode($this->generateDefaultResponses());
    }
}
