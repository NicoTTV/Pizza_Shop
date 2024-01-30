class EtapeCommandeAction {

    constructor(commandeService) {
        this.commandeService = commandeService;
    }

    execute(req, res) {
        try {
            const etapeCommande = req.body.etape;
            const idCommande = req.params.id;
            this.commandeService.changerEtatCommande(idCommande, etapeCommande);
        } catch (err) {
            res.status(500).json({ error: err.message });
        }
    }
}