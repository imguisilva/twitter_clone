<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

        $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		$this->render('index');
	}

    public function inscreverse() {

        $this->view->usuario = array( //quando a página inscrever-se for acessada diretamente, os campos abaixo serão limpos
            'nome' => '',
            'email' => '',
            'senha' => '',
        );

        $this->view->erroCadastro = false;
        
        $this->render('inscreverse');
    }

    public function registrar() {
        
        //receber os dados do formulário

        $usuario = Container::getModel('Usuario');

        $usuario->__set('nome', $_POST['nome']);
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', md5($_POST['senha'])); //o método md5 criptografa a senha para que não fique exposta para outras pessoas

        if($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0) { //se validarCadastro for TRUE e se se a quantidade de usuários for igual à 0 (nenhuma), prossiga para a criação, faça:
            $usuario->salvar(); //criar o usuário

            $this->render('cadastro'); //se o cadastro for realizado com sucesso, essa view será renderizada
        } 
        
        else { //do contrário volte para a tela de login

            $this->view->usuario = array( //caso dê erro na criação, os dados já preenchidos nos campos não serão perdidos, apenas haverá um mensagem de erro
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'senha' => $_POST['senha'],
            );

            $this->view->erroCadastro = true; //exibe uma mensagem de erro na página inscreverse.phtml para o usuário embaixo do botão inscrever-se

            $this->render('inscreverse');
        }    
    }

}


?>