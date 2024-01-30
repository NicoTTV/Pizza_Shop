export class CommandesAction {
    constructor(commandeService) {
        this.commandeService = commandeService;
    }

    async execute(req, res) {
        try {
            const commandes = await this.commandeService.listerCommandes();
            res.json(commandes);
        } catch (err) {
            res.status(500).json({ error: err.message });
        }
    }
}
