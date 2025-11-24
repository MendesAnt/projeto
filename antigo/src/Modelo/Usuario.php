<?php
    class Usuario
    {
        private ?int $id;
        private string $nomeReal;
        private string $nomeUsuario;
        private string $perfil;
        private string $email;
        private string $senha;

        //Método construtor
        public function __construct(?int $id,string $nomeReal,string $nomeUsuario, string $perfil, string $email, string $senha){
            $this->id = $id;
            $this->nomeReal = $nomeReal;
            $this->nomeUsuario = $nomeUsuario;
            $this->perfil = $perfil;
            $this->email = $email;
            $this->senha = $senha;
        }

        public function getId(): int
        {
            return $this->id;
        }

        public function getNomeReal(): string
        {
            return $this->nomeReal;
        }

        public function getNomeUsuario(): string
        {
            return $this->nomeUsuario;
        }

        public function getPerfil(): string
        {
            return $this->perfil;
        }

        public function getEmail(): string
        {
            return $this->email;
        }

        public function getSenha(): string
        {
            return $this->senha;
        }
    }

?>