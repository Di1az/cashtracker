type BudgetType = 'goal' | 'general'

export type Budget = {
    id: number
    name: string
    amount: string
    type: BudgetType
    created_at: string
}