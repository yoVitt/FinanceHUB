# Diagrama Entidade-Relacionamento

```mermaid
erDiagram
    DESPESAS ||--o{ PAGAMENTOS : possui

    DESPESAS {
        bigint id PK
        text descricao
        varchar categoria
        decimal valor
        date vencimento
        varchar status
        varchar imagem
        timestamp created_at
        timestamp updated_at
    }

    PAGAMENTOS {
        bigint id PK
        bigint despesa_id FK
        date data_pagamento
        decimal valor_pago
        text observacoes
        timestamp created_at
        timestamp updated_at
    }

    USERS {
        bigint id PK
        varchar name
        varchar email
        varchar password
        timestamp created_at
        timestamp updated_at
    }
```

## Relacionamentos

- Uma despesa pode possuir zero ou vários pagamentos.
- Cada pagamento pertence obrigatoriamente a uma despesa.
- A exclusão de uma despesa exclui seus pagamentos por `ON DELETE CASCADE`.
- Usuários servem apenas para autenticação. Os registros financeiros são compartilhados.

