<?php
/**
 * Created by IntelliJ IDEA.
 * User: ivanpianetti
 * Date: 18/6/18
 * Time: 19:48
 */

namespace QueryBuilder\MuchWow;

use Symfony\Component\HttpFoundation\Request;

class MuchWow
{
    /**
     * Transforma los parámetros REST en una lista de criterias necesarias para QueryBuilder.
     * @param Request $request
     * @return array
     */
    public static function obtenerCriterias(Request $request)
    {
        $criterias = [];
        foreach ($request->query->all () as $param => $value){
            if (is_array ($value)){
                foreach ($value as $criteria => $valor){
                    array_push ($criterias, [$param, MuchWow::mapCriteria ($criteria), $valor]);
                }
            }else{
                if ($value === true || strtolower ($value) === 'true'){
                    $value = '1';
                }elseif ($value === false || strtolower ($value) === 'false'){
                    $value = '0';
                }
                array_push ($criterias, [$param, MuchWow::mapCriteria ('eq'), $value]);
            }
        }
        return $criterias;
    }

    /**
     * Traduce las criterias REST en criterias de QueryBuilder
     * @param $criteria
     * @return string
     */
    private static function mapCriteria($criteria)
    {
        $resp = '=';
        switch(strtolower ($criteria)){
            case 'ge':
                $resp = '>=';
                break;
            case 'gt':
                $resp = '>';
                break;
            case 'le':
                $resp = '<=';
                break;
            case 'lt':
                $resp = '<';
                break;
            case 'ne':
                $resp = '!=';
                break;
            case 'like':
            case 'ilike':
                $resp = 'LIKE';
                break;
            default;
                break;
        }

        return $resp;
    }
}