<?php

/**
 * Class for the 'display_line' parser hooks.
 * 
 * @since 0.7
 * 
 * @file Maps_DisplayLine.php
 * @ingroup Maps
 * 
 * @author Kim Eik
 */
class MapsDisplayLine extends MapsDisplayPoint {

    /**
     * No LSB in pre-5.3 PHP *sigh*.
     * This is to be refactored as soon as php >=5.3 becomes acceptable.
     */
    public static function staticInit( Parser &$parser ) {
        $instance = new self;
        return $instance->init( $parser );
    }


    /**
     * Gets the name of the parser hook.
     *
     * @since 0.4
     *
     * @return string or array of string
     */
    protected function getName(){
        return 'display_line';
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function render( array $parameters ) {
        // Get the instance of the service class.
        $service = MapsMappingServices::getServiceInstance($parameters['mappingservice']);

        // Get an instance of the class handling the current parser hook and service.
        $mapClass = $service->getFeatureInstance( $this->getName() );

        return $mapClass->renderMap( $parameters, $this->parser );
    }

    /**
     * Returns an array containing the parameter info.
     * @see ParserHook::getParameterInfo
     *
     * @since 0.7
     *
     * @return array
     */
    protected function getParameterInfo( $type ) {
        global $egMapsDefaultServices;

        $params = parent::getParameterInfo($type);

        $params['mappingservice']->setDefault( $egMapsDefaultServices[$this->getName()] );
        $params['mappingservice']->addManipulations( new MapsParamService( $this->getName() ) );

        $params['lines'] = new ListParameter( 'lines', ';' );
        $params['lines']->setDefault(array());
        $params['lines']->addCriteria(new CriterionLine());
        $params['lines']->addManipulations( new MapsParamLine() );

        $params['copycoords'] = new Parameter(
            'copycoords',
            Parameter::TYPE_BOOLEAN
        );
        $params['copycoords']->setDefault(false);
        $params['copycoords']->setDoManipulationOfDefault( false );


        $params['markercluster'] = new Parameter(
            'markercluster',
            Parameter::TYPE_BOOLEAN
        );
        $params['markercluster']->setDefault(false);
        $params['markercluster']->setDoManipulationOfDefault( false );

        return $params;
    }

    /**
     * Returns the list of default parameters.
     * @see ParserHook::getDefaultParameters
     *
     * @since 0.7
     *
     * @return array
     */
    protected function getDefaultParameters( $type ) {
        return array( 'coordinates','lines' );
    }
}