export default function createAddTransactionObject(changes)
{
  return {
    description: changes?.changedDescription,
    froms: changes?.fromsChanges?.newItems,
    tos: changes?.tosChanges?.newItems,
    amount: changes?.editingAmount && changes?.changedAmount,
    type: changes?.changedType?.id
  }
}
