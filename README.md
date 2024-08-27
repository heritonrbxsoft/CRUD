# JSON CRUD Project

Este é um projeto simples de **CRUD (Create, Read, Update, Delete)** onde todas as informações são armazenadas em um arquivo **JSON**. O objetivo deste projeto é demonstrar como manipular dados em um arquivo JSON usando **PHP**.

## Funcionalidades

- **Criar**: Adiciona novos registros ao arquivo JSON.
- **Ler**: Exibe os registros armazenados no arquivo JSON.
- **Atualizar**: Modifica registros existentes no arquivo JSON.
- **Deletar**: Remove registros do arquivo JSON.

## Tecnologias Utilizadas

- **PHP**: Linguagem principal usada para manipular os dados.
- **JSON**: Formato de arquivo usado para armazenar os dados.
- **HTML/CSS**: Utilizados para a interface do usuário.

## Como Usar

### Rodando a Aplicação com XAMPP no Windows

1. **Instale o XAMPP**:
   - Baixe e instale o XAMPP a partir do [site oficial](https://www.apachefriends.org/index.html).

2. **Inicie o XAMPP**:
   - Abra o "XAMPP Control Panel" e inicie os módulos Apache e MySQL.

3. **Coloque a Aplicação na Pasta `htdocs`**:
   - Navegue até o diretório onde o XAMPP foi instalado, normalmente `C:\xampp\`.
   - Coloque sua aplicação na pasta `htdocs`, por exemplo, `C:\xampp\htdocs\meu_projeto`.

4. **Acesse a Aplicação**:
   - No navegador, acesse `http://localhost/meu_projeto`.

### Rodando a Aplicação em um Servidor Linux com Apache

1. **Instale o Apache, PHP e MySQL**:
   - Execute o seguinte comando no terminal:
     ```bash
     sudo apt update
     sudo apt install apache2 php libapache2-mod-php php-mysql
     ```

2. **Coloque a Aplicação na Pasta do Servidor Web**:
   - Copie sua aplicação para o diretório `/var/www/html`:
     ```bash
     sudo cp -r /caminho/do/seu/projeto /var/www/html/meu_projeto
     ```

3. **Configure as Permissões**:
   - Defina as permissões corretas para o diretório e arquivos:
     ```bash
     sudo chown -R www-data:www-data /var/www/html/meu_projeto
     sudo chmod -R 755 /var/www/html/meu_projeto
     ```

4. **Acesse a Aplicação**:
   - No navegador, acesse `http://localhost/meu_projeto`. Se configurou um virtual host, acesse `http://meu_projeto.local`.

## Estrutura do Projeto

- `index.php`: Página principal que exibe a interface do CRUD.
- `dados.json`: Arquivo onde os dados são armazenados.

## Contribuindo

Sinta-se à vontade para contribuir com este projeto. Para isso:

1. Faça um fork deste repositório.
2. Crie uma branch para a sua feature ou correção:
   ```bash
   git checkout -b feature/MinhaFeature
