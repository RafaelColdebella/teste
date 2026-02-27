ROTAS DA API - GUIA COMPLETO

1. Criar Participants
2. Criar Payments
3. Consultar Profits

Todas as rotas estão prontas no Collection do postman

1. PARTICIPANTS (Participantes)

Rota: POST /api/participants

Body payer:
{
  "name": "Sabadinho Silva",
  "email": "sabadinho@example.com",
  "document": "12345678900",
  "balance": "100",
  "type": "payer"
}

Body receiver:
{
  "name": "Maria Isabel",
  "email": "maria@example.com",
  "document": "98765432100",
  "balance": "0",
  "type": "receiver"
}

1.2 LISTAR TODOS OS PARTICIPANTES (GET)
Rota: GET /api/participants

1.3 VISUALIZAR PARTICIPANTE ESPECÍFICO (GET)
Rota: GET /api/participants/{id}

1.4 ATUALIZAR PARTICIPANTE (PUT)
Rota: PUT /api/participants/{id}

Body (OPCIONAL - envie apenas os campos que deseja atualizar):

Exemplo:
{
  "name": "Sabadinho Atualizado"
}

1.5 DELETAR PARTICIPANTE (DELETE)
Rota: DELETE /api/participants/{id}



2. PAYMENTS (Pagamentos)

Você deve ter criado pelo menos 2 participantes ANTES de criar pagamentos


2.1 CRIAR PAGAMENTO (POST)

Precisará dos IDs dos participantes (payer_id e receiver_id)

Exemplo (assumindo que criou participants com IDs 1 e 2):

Body Exemplo
{
  "value": 1000.00,
  "payment_type": "transfer",
  "tax": 2,
  "payer_id": 1,
  "receiver_id": 2
}


2.2 LISTAR TODOS OS PAGAMENTOS (GET)
Rota: GET /api/payments


2.3 VISUALIZAR PAGAMENTO ESPECÍFICO (GET)
Rota: GET /api/payments/{id}


2.4 ATUALIZAR PAGAMENTO (PUT)
Rota: PUT /api/payments/{id}


Body (OPCIONAL - envie apenas os campos que deseja atualizar):
{
  "value": 1200.00,
  "tax": 3
}


2.5 DELETAR PAGAMENTO (DELETE)
Rota: DELETE /api/payments/{id}


3. PROFIT (Lucro/Ganho)

3.1 OBTER LUCRO TOTAL (GET)
Rota: GET /api/profit/total

3.2 OBTER LUCRO POR PERÍODO (POST)
Envie um JSON com `start_date` e/ou `end_date` (YYYY-MM-DD); 
Caso apenas uma das datas seja fornecida o intervalo se estende
até o início ou fim do conjunto de dados.

Rota: POST /api/profit/total


Somente início
{ "start_date": "2026-01-01" }

Somente fim
{ "end_date": "2026-12-31" }

Ambos
{ "start_date": "2000-01-01", "end_date": "2026-12-31" }


PARA FUNCIONAMENTO DO PROJETO:
- Copie o .env.example e altere o nome para .env
- Insira a senha do banco de dados no env
- Inicie o dev container com o control + shift + p
- Rode as migrations com php artisan migrate
- Inicie o projeto com php artisan serve