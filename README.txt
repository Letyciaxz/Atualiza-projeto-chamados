SISTEMA DE CHAMADOS TÉCNICOS

1. Copie a pasta chamados para C:\xampp\htdocs\
2. Inicie Apache no XAMPP.
3. Acesse http://localhost/chamados/
4. Preencha o formulário e consulte a lista.

O arquivo chamados.csv será criado automaticamente após o primeiro envio.

ROTEIRO DE TESTES
- Formulário completo: deve gravar o chamado e exibir um código.
- Nome vazio: deve mostrar mensagem de erro.
- Descrição "ruim": deve rejeitar por ter menos de 10 caracteres.
- Digite <b>teste</b>: deve aparecer como texto, sem virar negrito.
- Use os filtros Alta, Média e Baixa na lista.
- Confira o fluxo: index.html -> salvar.php -> chamados.csv -> lista.php.
