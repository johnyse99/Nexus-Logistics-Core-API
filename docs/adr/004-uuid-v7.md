# ADR-004: Usage of UUID v7

## Context

We need unique identifiers for our entities (Shipping Orders) that are sortable by time to improve database indexing performance. Standard UUID v4 is random and causes fragmentation in B-Tree indexes.

## Decision

We will use UUID v7 (Unix Epoch time based).

## Consequences

- Better insert performance in clustered indexes (like PostgreSQL/MySQL primary keys).
- Chronological sorting capability built-in.
- Requires `symfony/uid` component.
