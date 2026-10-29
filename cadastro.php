<?php

require_once 'db.php';
//require_once significa: "Traga o código desse arquivo obrigatoriamente, mas se você já trouxe ele antes nesta mesma página, não precisa carregar de novo!"


$mensagem = '';
//Cria a variável para avisos,Por que usamos: Se acontecer algum problema depois (tipo "e-mail já cadastrado"), guardamos o texto de aviso aqui. Criar ela vazia agora evita que o HTML lá embaixo reclame que a variável não existe.

//agora vamos dar aquela testada,sim comando nada ve ne, fazer oq , reclama com o cara q criou php

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

/*1. De onde vem essa tal de "supervariável"?
Quando você programa em PHP, você pode criar suas próprias variáveis (tipo $nome = "Gabriel").
Mas o próprio PHP já vem "de fábrica" com algumas variáveis especiais pré-instaladas. Elas são chamadas de supervariáveis (ou superglobais) só por um motivo simples: elas já existem automaticamente, sem você precisar criar nada!
O próprio servidor (o XAMPP/PHP) cria e preenche essas variáveis sozinho toda vez que alguém entra no seu site.
A $_SERVER é uma dessas variáveis de fábrica.
2. O que é a $_SERVER na prática?
Pense na $_SERVER como se fosse uma ficha de cadastro do visitante que o PHP preenche automaticamente no segundo em que alguém entra na sua página.
Nessa ficha, o PHP anota coisas como:
Qual o IP do visitante?
Qual navegador ele está usando?
De que jeito ele abriu a página? (Isso é o mais importante para nós!)
3. O que é o ['REQUEST_METHOD']?
Como essa ficha $_SERVER tem muitas informações, nós usamos os colchetes [' ... '] para dizer qual linha da ficha queremos ler.
A linha ['REQUEST_METHOD'] significa literalmente "Método de Acesso".
Ela só guarda uma de duas palavras:
Guarda a palavra 'GET' se a pessoa só abriu o site normal (clicando num link ou digitando a URL).
Guarda a palavra 'POST' se a pessoa clicou no botão de enviar um formulário.
4. O que é o === 'POST'?
O === é apenas uma pergunta de igualdade: "É igual a...?"
Então quando escrevemos: $_SERVER['REQUEST_METHOD'] === 'POST'
Nós estamos apenas lendo a ficha e perguntando:
"PHP, a linha 'Método de Acesso' dessa ficha está marcada com a palavra 'POST'?"
💡 Juntando tudo em uma frase só:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
Significa simplesmente:
"SE (if) na ficha do acesso ($_SERVER), a linha de método (['REQUEST_METHOD']) for a palavra 'POST' (=== 'POST'), então roda o código de cadastro!"
o código de salvar no banco só ativa/liga quando o PHP percebe que a requisição veio do clique do botão (POST).
*/
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = ($_POST['senha']);
        
//1. O que é o $_POST?Assim como o $_SERVER, o $_POST é outra supervariável de fábrica do PHP.Quando o formulário envia os dados, o PHP pega o que foi digitado em cada campo e guarda dentro dessa "gaveta" $_POST.Os nomes das chaves ('nome', 'email', 'senha') vêm diretamente do atributo name="..." que colocamos nas tags <input> do HTML!Exemplo: <input type="text" name="nome"> $\rightarrow$ No PHP vira $_POST['nome'].2. O que faz o trim(...)?A função trim() serve para remover espaços em branco no início e no final de um texto.Exemplo prático: Se o usuário digitou sem querer " gabriel " (com espaços antes e depois), o trim() limpa e transforma em "gabriel".Por que usamos no Nome e no E-mail? Para evitar salvar e-mails com espaços invisíveis no banco de dados, o que causaria erros na hora do usuário tentar fazer login depois.Por que NÃO usamos no trim() na Senha? Porque na senha os espaços podem ser intencionais (se a pessoa quis colocar um espaço como parte do código secreto dela, devemos respeitar).3. As variáveis ($nome, $email, $senha)Nós criamos essas três variáveis para guardar os valores limpos e prontos para uso nas próximas linhas do código.💡 Resumo do Passo 3:Pegamos o que veio do formulário através do $_POST, limpamos os espaços desnecessários do nome e e-mail com trim(), e guardamos tudo em variáveis fáceis de usar.
    if (!empty($nome) && !empty($email) && !empty($senha)){
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        /*para entender o motivo, pense que a função password_hash() precisa saber de duas coisas para trabalhar:
    1º parâmetro ($senha): O QUE vai ser criptografado.
    2º parâmetro (PASSWORD_DEFAULT): QUAL ALGORITMO (fórmula matemática) o PHP deve usar para embaralhar essa senha.
    Por que precisamos dizer o algoritmo?
    O PHP conhece vários métodos de criptografia diferentes (como BCRYPT, ARGON2I, ARGON2ID). A função password_hash() não adivinha qual deles você quer usar, por isso exige que você especifique um.
    Ao colocar PASSWORD_DEFAULT, você está dizendo ao PHP:
    "PHP, use o algoritmo padrão e mais recomendado da comunidade hoje!" (atualmente o BCRYPT).E respondendo à sua pergunta: "Tem que explicar qual ele vai usar?"
SIM, obrigatoriamente!
A função password_hash() no PHP foi programada para sempre exigir 2 coisas dentro dos parênteses:
password_hash( $senha , PASSWORD_DEFAULT );
//              ▲               ▲
//              │               └─ 2. QUAL algoritmo criptográfico usar
//              └─ 1. O QUE vai ser criptografado
Se você escrever apenas password_hash($senha), o PHP para o programa e dá um erro dizendo que está faltando o segundo parâmetro obrigatório.

*/ 
        // 6. Prepara o comando SQL (Prepared Statement)
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $pdo->prepare($sql);
        // 7. Executa e grava no MySQL
        $smt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha_hash
        ]);

        $mensagem = "Usuário cadastrado com sucesso!";



/*📄 A Analogia do Formulário em Branco
Imagina que para cadastrar uma pessoa no banco, existe uma regra:
Primeiro você cria a modelo do formulário com as lacunas em branco (:nome, :email, :senha).
Entrega o formulário em branco para o segurança (MySQL) aprovar a estrutura.
Depois, você entrega os papeizinhos com os dados para preencher dentro dessas lacunas.
É exatamente isso que os Passos 6 e 7 fazem!
1️⃣ Passo 6: Criar a folha em branco e aprovar no MySQL
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
$stmt = $pdo->prepare($sql);
INSERT INTO usuarios (nome, email, senha): É o comando em linguagem SQL que diz ao MySQL: "Quero inserir uma nova linha na tabela de usuários nas colunas nome, email e senha".
:nome, :email, :senha: São os placeholders (ou "etiquetas de lacuna"). Em vez de colocar os dados do usuário direto na frase, colocamos essas máscaras temporárias.
$pdo->prepare($sql): Envia esse formulário com lacunas para o MySQL. O MySQL analisa a frase e tranca a estrutura.
🛡️ Por que fazemos o prepare()? (Muito importante para a defesa!)
Porque se um hacker tentar digitar um comando malicioso no campo de nome (tipo '; DROP TABLE usuarios; --), o MySQL já travou a estrutura antes! O MySQL vai entender que aquilo é apenas um texto simples e não um comando para apagar seu banco. Essa técnica se chama Prepared Statement e evita o SQL Injection.
2️⃣ Passo 7: Preencher as lacunas e salvar
$stmt->execute([
    ':nome'  => $nome,
    ':email' => $email,
    ':senha' => $senha_hash
]);
$stmt: É a variável que guardou o nosso comando preparado lá do Passo 6.
->execute([...]): Manda o MySQL executar a gravação, passando um array (uma lista de pares) que diz exatamente o que vai em cada lacuna:
A lacuna :nome recebe o valor de $nome.
A lacuna :email recebe o valor de $email.
A lacuna :senha recebe o valor de $senha_hash (a senha já criptografada!).
Quando o $stmt->execute() roda, o registro é oficialmente gravado na tabela do MySQL!
💡 Resumo em duas frases:
Passo 6 (prepare): Monta o comando SQL seguro usando "lacunas" (:nome, :email, :senha) e manda o MySQL travar a estrutura.
Passo 7 (execute): Entrega os valores reais para preencher essas lacunas e salva tudo no banco de dados.

*/
    } else{
        // se o cara esqueceu de botar algo ja reclama ja
        $mensagem = "Preencha todo os campos";
    }

    
}

?>

    
<!-- ❌ O que aconteceria SE NÃO colocássemos $mensagem = ''; lá no topo?
Cenário: Você abriu o site pela primeira vez (Acesso GET)
O PHP lê o arquivo do topo.
Chega no if ($_SERVER['REQUEST_METHOD'] === 'POST').
Como você acabou de abrir o site e não clicou no botão ainda, o PHP pula todo o bloco do if (e pula o else também!).
O PHP continua descendo o código até chegar no seu HTML, onde está escrito:
<php if (!empty($mensagem)): ?>
  <p><php echo $mensagem; ?></p>
<php endif; ?>
O BUM! 💥: O PHP tenta ler a variável $mensagem, mas ela nunca foi criada (pois o código do else foi pulado!).
O PHP trava e mostra uma tela de aviso amarela/preta dizendo:
Warning: Undefined variable $mensagem (Aviso: Variável $mensagem não definida). -->



<!-- Aqui tem só o HTML por enquanto, o PHP vamos colocar depois -->

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
</head>

<body>
    <h2>Criar Nova Conta</h2>

    <!-- Sim, comentário em HTML é com essa tag esquisita mesmo kkk -->
    <!-- Formulário de cadastro: -->
    <!-- method="POST": envia os dados escondidos no corpo da requisição (muito mais seguro pra senhas do que GET, que mostra tudo na URL) -->
    <form action="cadastro.php" method="POST">

        <div>
            <label>Nome: </label><br>
            <!-- name="nome": MUITO IMPORTANTE! É o nome que o PHP usa pra capturar o que foi digitado ($_POST['nome']) -->
            <!-- required: impede de enviar o formulário se o campo estiver vazio -->
            <input type="text" name="nome" required>
        </div>
        <br>

        <div>
            <label>E-mail: </label><br>
            <!-- name="email": nome que o PHP usa pra pegar o e-mail digitado ($_POST['email']) -->
            <input type="email" name="email" required>
        </div>
        <br>

        <div>
            <label>Senha: </label><br>
            <!-- type="password": troca as letras por bolinhas pra ninguém em volta ver a senha digitada -->
            <!-- name="senha": nome que o PHP usa pra capturar a senha ($_POST['senha']) -->
            <input type="password" name="senha" required>
        </div>
        <br>

        <!-- type="submit": botão que dispara o envio do formulário pro arquivo do action (cadastro.php) -->
        <button type="submit">Cadastrar</button>

        <p>Já tem uma conta? <a href="login.php">Faça Login Aqui</a></p>
    </form>
</body>

</html>
