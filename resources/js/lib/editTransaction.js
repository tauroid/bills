export default function createEditTransactionObject(changes) {
  return {
    changedDescription: changes?.changedDescription,
    fromsChanges: changes?.fromsChanges,
    tosChanges: changes?.tosChanges,
    changedAmount: changes?.editingAmount && changes?.changedAmount,
    changedType: changes?.changedType?.id
  }
}
