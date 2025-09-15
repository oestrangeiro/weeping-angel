<?php

namespace App\Traits;

// Trait para reultilização de métodos entre classes
// @author Mateus 'oestrangeiro' Almeida
// 08-2025

trait Validate {

    // Não vou questionar sua cognição
    public function isSomeValueNull(array $array): bool {

        foreach($array as $key => $value){
            if($value === null || $value === '') { // evita falsy
                return true;
            }
        }
        
        // Caso não haja nenhum campo vazio (o que é o esperado), retorna false
        return false;
    }

    // Método que retorna se um é válido ou não
    public function isThisEmailValid(string $email): bool {

        $isAnValidEmail = filter_var($email, FILTER_VALIDATE_EMAIL);        

        if(!$isAnValidEmail){
            return false;
        }

        return true;
    }

    // Método que sanitiza um email
    public function sanitizeEmail(string $email): string {

        $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);

        return $emailSanitized;
    }

    // Método para sanitizar/escapar uma entrada genérica como nomes
    public function escapeEntry(string $data): string {
        // Remove números e caracteres especiais
        
        $dataEscaped = preg_replace('/[^\p{L} ]/u', '', $data);

        // Evita XSS
        $dataEscaped = htmlspecialchars($dataEscaped, ENT_QUOTES, 'UTF-8');

        return $dataEscaped;
    }

    // Não vou questionar sua cognição...
    public function removeSpaces(string $password): string {

        $passwordWithoutSpaces = str_replace(' ', '', $password);

        return $passwordWithoutSpaces;
    }

    // Método que verifica se a senha é valida com base naqueles caracteres
    // caso o cara tenha inserido uma senha qe possua um caractere diferente daqueles,
    // retorna false
    public function isThisPasswordValid(string $password): bool {
        return !preg_match('/[^a-zA-Z0-9!@#$%&*()_=+-]/', $password);
    }

    // Método para sanitizar um número de telefone
    public function sanitizePhoneNumber(string $phoneNumber): string {
        // Remove os caracteres especiais mais communs inseridos
        // pelos usuários quando vão enviar um número de telefone
        $phoneNumber = str_replace(' ', '', $phoneNumber);
        $phoneNumber = str_replace('(', '', $phoneNumber);
        $phoneNumber = str_replace(')', '', $phoneNumber);
        $phoneNumber = str_replace('-', '', $phoneNumber);
        $phoneNumber = str_replace('+', '', $phoneNumber);

        return $phoneNumber;
    }

    // Método para validar número de telefone
    public function validatePhoneNumber(string $phoneNumber): bool {

        // se o telefone for menor que 9 ou maior que 11 digitos
        // é um telefone inválido
        if( (strlen($phoneNumber) < 9) || (strlen($phoneNumber) > 11) ){
            return false;
        }

        // Checa se o usuário (mesmo após a sanitização) enviou algum caractere que não número
        return !preg_match('/[^0-9]/', $phoneNumber);
    }

    // Método para sanitizar um CPF,
    // removendo pontos e hífens
    public function sanitizeCPF(string $cpf): string {

        $cpf = str_replace(' ', '', $cpf);
        $cpf = str_replace('.', '', $cpf);
        $cpf = str_replace('-', '', $cpf);

        return $cpf;
    }

    // Método que verifica se há apenas números em uma string informada
    public function onlyDigits(array|string $data): bool{

        // Trata essa merda como array
        if(is_array($data)){
            foreach($data as $d){
                $thereAreOnlyDigits = !preg_match('/[^0-9]/', $d);
    
                if(!$thereAreOnlyDigits){
                    return false;
                }
            }
        }

        // Trata como string
        elseif(is_string($data)){

            $thereAreOnlyDigits = !preg_match('/[^0-9]/', $data);
    
            if(!$thereAreOnlyDigits){
                return false;
            }
        }

        // se, sendo array ou só uma string e possuir APENAS números, retorna VERDADEIRO
        return true;
    }
    // Verifica se, após a remoção dos caracteres especiais, é um cpf válido
    public function isAValidCPF(string $cpf): bool {
        
        $cpfLenght = strlen($cpf);

        if($cpfLenght !== 11){
            return false;
        }

        // se o tamanho for correto, verifico se o cpf possui apenas dígitos

        $thereAreOnlyDigits = !preg_match('/[^0-9]/', $cpf);

        if(!$thereAreOnlyDigits){
            return false;
        }

        // Existem CPF inválidos conhecidos, eles possuem números iguais
        // Ex.: 000.000.000-00, 111.111.111-11 etc...
        // Iremos verificar se o cpf atende essa condição, caso sim, retorno que é inválido

        // Depois eu penso em uma solução melhor...
        $enumInvalidCPFs = [
            '00000000000',
            '11111111111',
            '22222222222',
            '33333333333',
            '44444444444',
            '55555555555',
            '66666666666',
            '77777777777',
            '88888888888',
            '99999999999'
        ];

        for($i = 0; $i < sizeof($enumInvalidCPFs); $i++){
            if($cpf == $enumInvalidCPFs[$i]){
                return false;
            }
        }


        // Agora sim começa a validação do CPF de fato

        // Validação do primeiro dígito:
        // Multiplica-se os 9 primeiro digitos pela sequencia descrescente de 10 à 2 e soma os resultados
        
        // Valor da soma das multiplicações dos dígitos
        $value = 0;

        for($i = 10, $pos = 0; $i >= 2; $i--, $pos++){
            $value += (int)$cpf[$pos] * $i;
        }

        // Agora multiplicamos esse valor por 10 e logo em seguida dividimos por 11
        // O que nos interessa é o resto da divisão
        // NOTA: caso este seja igual a 10, consideramos 0

        $firstDigit = (int)(($value * 10) % 11);

        (string)$firstDigit == '10' ? $firstDigit = '0' : null;

        // Checamos se o dígito bate com o primeiro dígito verificador
        if((string)$firstDigit !== $cpf[9]){
            return false;
        }

        $value = 0;
        // Agora, para verificarmos o segundo dígito, fazemos
        // Consideramos os 9 primeiros digitos, mais o primeiro dígito verificador
        // Multiplicamos esses 10 números pela sequencia de 11 à 2
        for($i = 11, $pos = 0; $i >= 2; $i--, $pos++){
            $value += (int)$cpf[$pos] * $i;
        }

        // Agora multiplicamos esse numero por 10 e em seguida pegamos o resto da divisão por 11
        $secondDigit = (int)(($value * 10) % 11);

        (string)$secondDigit == '10' ? $secondDigit = '0' : null;

        if((string)$secondDigit !== $cpf[10]){
            return false;
        }

        // O cpf é válido
        return true;
    }

    // Método que verifica se o usuário enviou uma imagem
    public function useDefaultPfp($profilePicture): bool {

        $useDefaultProfilePicture = false;

        // Checa se o usuário enviou uma imagem personalizada ou não na criação da conta
        if(!$profilePicture || $profilePicture->getError()){
            // Caso ele não tenha enviado uma imagem própria,
            // acabo usando uma imagem padrão
            $useDefaultProfilePicture = true;
        }
        // Se não tiver mandado a imagem: retorna 1
        // Se tiver mandado, retorna 0
        return $useDefaultProfilePicture;
    }

    // Sanitizando CNPJ alfanumerico
    public function sanitizeCNPJ(string $cnpj): string {

        // removendo os caracteres indesejados
        $cnpj = str_replace(' ', '', $cnpj);
        $cnpj = str_replace('.', '', $cnpj);
        $cnpj = str_replace('-', '', $cnpj);
        $cnpj = str_replace('/', '', $cnpj);

        return $cnpj;
    }

    // Validando CNPJ
    // Nesse caso, vai ser uma validação bem da fuleragem,
    // Vou só checar se o tamanho está certo e retornar true
    public function isAValidCNPJ(string $cnpj): bool {

        
        $cnpjLenght = strlen($cnpj);

        if($cnpjLenght !== 14){
            return false;
        }

        return true;

    }
}