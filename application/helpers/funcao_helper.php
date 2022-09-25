<?php


defined('BASEPATH') or exit('Ação não permitida');


/**
 * @param $string
 * @return string
 */
function formata_data_banco_com_hora($string): string
{

	$dia_sem = date('w', strtotime($string));

	if ($dia_sem == 0) {
		$semana = "Domingo";
	} elseif ($dia_sem == 1) {
		$semana = "Segunda-feira";
	} elseif ($dia_sem == 2) {
		$semana = "Terça-feira";
	} elseif ($dia_sem == 3) {
		$semana = "Quarta-feira";
	} elseif ($dia_sem == 4) {
		$semana = "Quinta-feira";
	} elseif ($dia_sem == 5) {
		$semana = "Sexta-feira";
	} else {
		$semana = "Sábado";
	}

	$dia = date('d', strtotime($string));

	$mes_num = date('m', strtotime($string));

	$ano = date('Y', strtotime($string));
	$hora = date('H:i', strtotime($string));

	return $dia . '/' . $mes_num . '/' . $ano . ' ' . $hora;
}

/**
 * @param $string
 * @return string
 */
function formata_data_banco_sem_hora($string): string
{

	$dia_sem = date('w', strtotime($string));

	if ($dia_sem == 0) {
		$semana = "Domingo";
	} elseif ($dia_sem == 1) {
		$semana = "Segunda-feira";
	} elseif ($dia_sem == 2) {
		$semana = "Terça-feira";
	} elseif ($dia_sem == 3) {
		$semana = "Quarta-feira";
	} elseif ($dia_sem == 4) {
		$semana = "Quinta-feira";
	} elseif ($dia_sem == 5) {
		$semana = "Sexta-feira";
	} else {
		$semana = "Sábado";
	}

	$dia = date('d', strtotime($string));

	$mes_num = date('m', strtotime($string));

	$ano = date('Y', strtotime($string));
	$hora = date('H:i', strtotime($string));

	return $dia . '/' . $mes_num . '/' . $ano;
}
