<?php

use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author Ricardo Fiorani
 */
class DataHelper {

    const TABLE_SUFIX = 'tbl_';

    /**
     * @var PDO 
     */
    private $db;
    private $baseUrl;
    private $debug;

    function __construct(PDO $db, RequestManager $cpr, $debug = false) {
        $this->db = $db;
        $this->baseUrl = $cpr->getBaseUrl();
        $this->debug = $debug;
    }

    /**
     * FUNÇÃO QUE RETORNA O verRegistroPadrao em forma de objeto
     */
    public function ver($tabela, $id = 0, $checar = false) {
        $d = $this->array_to_object($this->verRegistroPadrao(self::TABLE_SUFIX . $tabela, $id));
        if (($checar && false == $d instanceof stdClass) || ($checar && $id != $d->id)) {
            throw new ResourceNotFoundException;
        } else {
            return $d;
        }
    }

    /**
     * FUNÇÃO QUE RETORNA O retornoRegistroPadrao em forma de objeto
     */
    public function listar($tabela, $url = '', $pagina = 1, $qtd_registros = 0, $where_custom = 'where ativo = "Sim"', $campo_ordem = 'ordem', $campo_group = '') {
        return $this->array_to_object($this->retornoRegistroPadrao(self::TABLE_SUFIX . $tabela, $url, $pagina, $qtd_registros, $where_custom, $campo_ordem, $campo_group));
    }

    public function redirect($url, $interno = true) {
        //Caso parametro URL esteja em branco será redirecionado para a raíz (Home)
        if ($interno) {
            header('Location: ' . $this->baseUrl . $url);
        } else {
            header('Location: ' . $url);
        }
        exit;
    }

    public function gerarGaleria($data, $caminho = '', $retorno_unico = false) {
        $img_cat = array();
        if (isset($data)) {
            $imagens = explode(';', trim($data));
            foreach ($imagens as $key => $value) {
                if (!empty($value)) {
                    $img_cat[$key]['nome_arquivo'] = $value;
                    $img_cat_expld = explode('.', $value);
                    $img_cat[$key]['nome'] = reset($img_cat_expld);
                    $img_cat[$key]['ext'] = end($img_cat_expld);
                    if (!empty($caminho)) {
                        $img_cat[$key]['url_arquivo'] = $this->baseUrl . '/uploads/' . $caminho . '/' . $value;
                        $img_cat[$key]['embeed'] = '<img src="' . $this->baseUrl . '/uploads/' . $caminho . '/' . $value . '"';
                        $img_cat[$key]['embeed1'] = '<img src="' . $this->baseUrl . '/uploads/' . $caminho . '/' . $img_cat[$key]['nome'] . '_1.' . $img_cat[$key]['ext'] . '">';
                        $img_cat[$key]['embeed2'] = '<img src="' . $this->baseUrl . '/uploads/' . $caminho . '/' . $img_cat[$key]['nome'] . '_2.' . $img_cat[$key]['ext'] . '">';
                        $img_cat[$key]['embeed3'] = '<img src="' . $this->baseUrl . '/uploads/' . $caminho . '/' . $img_cat[$key]['nome'] . '_3.' . $img_cat[$key]['ext'] . '">';
                        $img_cat[$key]['embeed4'] = '<img src="' . $this->baseUrl . '/uploads/' . $caminho . '/' . $img_cat[$key]['nome'] . '_4.' . $img_cat[$key]['ext'] . '">';
                        $img_cat[$key]['embeed5'] = '<img src="' . $this->baseUrl . '/uploads/' . $caminho . '/' . $img_cat[$key]['nome'] . '_5.' . $img_cat[$key]['ext'] . '">';
                        $img_cat[$key]['caminho'] = $this->baseUrl . '/uploads/' . $caminho . '/' . $value;
                        $img_cat[$key]['caminho1'] = $this->baseUrl . '/uploads/' . $caminho . '/' . $img_cat[$key]['nome'] . '_1.' . $img_cat[$key]['ext'];
                        $img_cat[$key]['caminho2'] = $this->baseUrl . '/uploads/' . $caminho . '/' . $img_cat[$key]['nome'] . '_2.' . $img_cat[$key]['ext'];
                        $img_cat[$key]['caminho3'] = $this->baseUrl . '/uploads/' . $caminho . '/' . $img_cat[$key]['nome'] . '_3.' . $img_cat[$key]['ext'];
                        $img_cat[$key]['caminho4'] = $this->baseUrl . '/uploads/' . $caminho . '/' . $img_cat[$key]['nome'] . '_4.' . $img_cat[$key]['ext'];
                        $img_cat[$key]['caminho5'] = $this->baseUrl . '/uploads/' . $caminho . '/' . $img_cat[$key]['nome'] . '_5.' . $img_cat[$key]['ext'];
                        $img_cat[$key]['arquivo'] = $value;
                        $img_cat[$key]['arquivo1'] = $img_cat[$key]['nome'] . '_1.' . $img_cat[$key]['ext'];
                        $img_cat[$key]['arquivo2'] = $img_cat[$key]['nome'] . '_2.' . $img_cat[$key]['ext'];
                        $img_cat[$key]['arquivo3'] = $img_cat[$key]['nome'] . '_3.' . $img_cat[$key]['ext'];
                        $img_cat[$key]['arquivo4'] = $img_cat[$key]['nome'] . '_4.' . $img_cat[$key]['ext'];
                        $img_cat[$key]['arquivo5'] = $img_cat[$key]['nome'] . '_5.' . $img_cat[$key]['ext'];
                    }
                }
            }
        }
        if ($retorno_unico == true) {
            return $this->array_to_object($img_cat[0]);
        } else {
            return $this->array_to_object($img_cat);
        }
    }

    public function array_to_object($array) {
        if (!empty($array)) {
            $obj = new stdClass;
            foreach ((array) $array as $k => $v) {
                if (is_array($v)) {
                    $obj->{$k} = $this->array_to_object($v); //RECURSION
                } else {
                    $obj->{$k} = $v;
                }
            }
            return $obj;
        } else {
            return $array;
        }
    }

    public function retornoRegistroPadrao($tabela, $url = '', $pagina = 1, $qtd_registros = 0, $where_custom = 'where ativo = "Sim"', $campo_ordem = 'ordem', $campo_group = '') {
        //Adaptação do Where_Custom para array
        if (is_array($where_custom)) {
            foreach ($where_custom as $key => $value) {
                if (!empty($whereTemp))
                    $whereTemp .= ' and ';
                else
                    $whereTemp = ' where ';
                $whereTemp .= $key . ' = "' . $value . '" ';
            }
            $where_custom = $whereTemp;
        }

        $expldTbl = explode('tbl_', $tabela);
        $tabelaSemSufixo = end($expldTbl);
        $sql = 'SELECT tbl.* FROM `' . $tabela . '` tbl ' . $where_custom;
        $sql_limit = '';
        if (!empty($campo_group)) {
            $sql .= ' group by ' . $campo_group;
        }

        if (trim(strtolower($campo_ordem)) == 'rand()') {
            $sql .=' order by ' . $campo_ordem . ' ';
        } else {
            $sql .=' order by tbl.' . $campo_ordem . ' ';
        }


        if (empty($pagina)) {
            $pagina = 1;
        }
        $primeiro_registro = ($pagina * $qtd_registros) - $qtd_registros;
        if ($primeiro_registro < 0) {
            $primeiro_registro = 0;
        }
        if ($qtd_registros != 0) {
            $sql_limit = ' LIMIT ' . $primeiro_registro . ',' . $qtd_registros;
        }
        $qry = $this->db->query($sql . $sql_limit . ';'); // or die('SQL EXECUTADO : ' . $sql . $sql_limit . ' ---------- ERROR : ' . $this->db->erroInfo());
        $erro_sql = $this->db->errorInfo();
        if (false === $qry && true === $this->debug) {
            throw new Exception($erro_sql[2], $erro_sql[1]);
        }

        $retorno = array();
        if (false !== $qry) {
            if ($qry->rowCount() > 0) {
                while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
                    foreach ($row as $key => $value) {
                        switch ($key) {
                            case 'nome' :
                                $row[$key] = utf8_encode($row[$key]);
                                $row[$key . '_url'] = $this->geraUrl($row[$key]);
                                break;
                            case 'nome_pt':
                            case 'nome_en':
                            case 'nome_es':
                                $row[$key] = utf8_encode($row[$key]);
                                $nm = explode('_', $key);
                                $row['nome_url_' . end($nm)] = $this->geraUrl($row[$key]);
                                break;
                            case 'descricao':
                                $row['resumo'] = utf8_encode($this->resumirStr($row['descricao']) . '...');
                                $row['descricao'] = utf8_encode($row['descricao']);
                                break;
                            case 'descricao_pt':
                            case 'descricao_en':
                            case 'descricao_es':
                                $row[$key] = utf8_encode($row[$key]);
                                $nm = explode('_', $key);
                                $row['resumo_' . end($nm)] = $this->resumirStr($row[$key]) . '...';
                                break;
                            case 'galeria' :
                                $row['galeria'] = $this->gerarGaleria($row['galeria'], $tabelaSemSufixo);
                                if (!empty($row['galeria'])) {
                                    $row['tem_galeria'] = true;
                                    $row['galeria_capa'] = reset($row['galeria']);
                                } else {
                                    $row['tem_galeria'] = false;
                                    $row['galeria_capa'] = new stdClass();
                                }
                                break;
                            case 'video_url' :
                                $row['video'] = $this->gerarVideo($row['video_url']);
                                break;
                            case 'imagem' :
                            case 'imagem_destaque' :
                            case 'overlay' :
                            case 'icone' :
                                $row[$key . '_original'] = $row[$key];
                                $row[$key] = $this->gerarGaleria($row[$key], $tabelaSemSufixo);
                                if (!empty($row[$key])) {
                                    $row[$key] = end($row[$key]); //Fix para não ter que ficar rodando como so fosse galeria
                                }
                                break;
                            case 'data_envio':
                                $row['data_envio'] = $this->formatarData($row['data_envio']);
                                break;
                            default :
                                $row[$key] = utf8_encode($value);
                                break;
                        }
                    }
                    $row['tabela'] = $tabelaSemSufixo;
                    array_push($retorno, $row);
                }
            }
        }


        /* paginacao------------------------------------------------------------------------------------------ */
        $paginacao = array();

        if ($qtd_registros != 0) {
            $sql_qtd = 'SELECT * FROM `' . $tabela . '` tbl ' . $where_custom;
            if (!empty($campo_group)) {
                $sql_qtd .= ' group by tbl.' . $campo_group;
            }

            if (trim(strtolower($campo_ordem)) == 'rand()') {
                $sql_qtd .=' order by ' . $campo_ordem . ' ';
            } else {
                $sql_qtd .=' order by tbl.' . $campo_ordem . ' ';
            }

            $qry_qtd = $this->db->query($sql_qtd);
            $total = $qry_qtd->rowCount();

            if ($total > $qtd_registros) {
                $paginacao = array(
                    'qtd_registros' => $qtd_registros,
                    'pagina' => $pagina,
                    'total' => $total,
                    'url' => $url,
                );
            }
        }
        return array(
            'registros' => $retorno,
            'qtd_registros' => count((array) $retorno),
            'paginacao' => $paginacao,
            'pagina' => $pagina,
            'pasta_imagem' => $tabelaSemSufixo,
            'tabela' => $tabelaSemSufixo,
            'sql' => $sql,
            'erro_sql' => $erro_sql
        );
    }

    public function verRegistroPadrao($tabela, $id = 0) {
        $pastasArray = explode('tbl_', $tabela);
        $tabelaSemSufixo = end($pastasArray);
        if (empty($id) || $id == 0) {
            $qryId = $this->db->query('select id from `' . $tabela . '` where ativo = "Sim" order by ordem');
            if (false !== $qryId) {
                $id = $qryId->fetch(PDO::FETCH_OBJ)->id;
            } else {
                return false;
            }
        } else {
            $id = intval($id);
        }
        $sqlQry = 'SELECT * FROM `' . $tabela . '` where ativo = "Sim" and id = ' . $id . '  order by ordem';
        $qry = $this->db->query($sqlQry);
        $erro_sql = $this->db->errorInfo();
        if (false === $qry) {
            throw new Exception($erro_sql[2], $erro_sql[1]);
        }
        $row = $qry->fetch(PDO::FETCH_ASSOC);
        if (empty($row)) {
            throw new ResourceNotFoundException;
//            throw new Exception(sprintf('Nenhum registro encontrado na tabela %s', $tabela), $code, $previous);
        }
        foreach ($row as $key => $value) {
            switch ($key) {
                case 'nome' :
                    $row[$key] = utf8_encode($row[$key]);
                    $row[$key . '_url'] = $this->geraUrl($row[$key]);
                    break;
                case 'nome_pt':
                case 'nome_en':
                case 'nome_es':
                    $row[$key] = utf8_encode($row[$key]);
                    $nm = explode('_', $key);
                    $row['nome_url_' . end($nm)] = $this->geraUrl($row[$key]);
                    break;
                case 'descricao':
                    $row['resumo'] = utf8_encode($this->resumirStr($row['descricao']) . '...');
                    $row['descricao'] = utf8_encode($row['descricao']);
                    break;
                case 'descricao_pt':
                case 'descricao_en':
                case 'descricao_es':
                    $row[$key] = utf8_encode($row[$key]);
                    $nm = explode('_', $key);
                    $row['resumo_' . end($nm)] = $this->resumirStr($row[$key]) . '...';
                    break;
                case 'galeria' :
                    $row['galeria'] = $this->gerarGaleria($row['galeria'], $tabelaSemSufixo);
                    if (!empty($row['galeria'])) {
                        $row['tem_galeria'] = true;
                        $row['galeria_capa'] = reset($row['galeria']);
                    } else {
                        $row['tem_galeria'] = false;
                        $row['galeria_capa'] = new stdClass();
                    }
                    break;
                case 'video_url' :
                    $row['video'] = $this->gerarVideo($row['video_url']);
                    break;
                case 'imagem' :
                case 'imagem_destaque' :
                case 'overlay' :
                case 'icone' :
                    $row[$key . '_original'] = $row[$key];
                    $row[$key] = $this->gerarGaleria($row[$key], $tabelaSemSufixo);
                    if (!empty($row[$key])) {
                        $row[$key] = end($row[$key]); //Fix para não ter que ficar rodando como so fosse galeria
                    }
                    break;
                case 'data_envio':
                    $row['data_envio'] = $this->formatarData($row['data_envio']);
                    break;
                default :
                    $row[$key] = utf8_encode($value);
                    break;
            }
        }
        $row['tabela'] = $tabelaSemSufixo;

        return $row;
    }

    public function geraUrl($input_str) {
        $input_str = $this->removeAcentos($input_str);
        $input_str = strtolower($input_str);
        $input_str = preg_replace("/[^a-z0-9_\s-]/", "", $input_str);
        $input_str = preg_replace("/[\s-]+/", " ", $input_str);
        $input_str = preg_replace("/[\s_]/", "-", $input_str);
        return $input_str;
    }

    public function stringUrlAmigavel($phrase, $maxLength = 50) {
        $result = strtolower($phrase);
        $result = preg_replace("/[^a-z0-9\s-]/", "", $result);
        $result = trim(preg_replace("/[\s-]+/", " ", $result));
        $result = trim(substr($result, 0, $maxLength));
        $result = preg_replace("/\s/", "-", $result);
        return $result;
    }

    public function removeAcentos($var) {
        return $this->normaliza($var);
    }

    public function normaliza($string) {
        $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $string = utf8_decode($string);
        $string = strtr($string, utf8_decode($a), $b);
        $string = strtolower($string);
        return utf8_encode($string);
    }

    public function resumirStr($texto, $n = 20) {
        $texto = strip_tags($texto);
        $texto = trim(preg_replace("/\s+/", " ", $texto));
        $word_array = explode(" ", $texto);
        if (count($word_array) <= $n)
            return implode(" ", $word_array);
        else {
            $texto = '';
            foreach ($word_array as $length => $word) {
                $texto.=$word;
                if ($length == $n)
                    break;
                else
                    $texto.=" ";
            }
        }
        return $texto;
    }

    public function getTextoGeral($cod) {
        $obj = $this->listar('sys_textos_gerais', '', 1, 1, ' where cod="' . $cod . '"');
        $texto = $obj->registros;
        if (empty($texto)) {
            $sql = "INSERT INTO `tbl_sys_textos_gerais` (`id`, `cod`, `nome`, `descricao`, `ordem`, `ativo`) VALUES (NULL, '$cod', '$cod', 'TEXTO GERENCIAVEL PELO PAINEL (COD:$cod)', '0', 'Sim');";
            $this->db->query($sql);
            return $this->getTextoGeral($cod);
        } else {
            return reset($texto)->descricao;
        }
    }

}
