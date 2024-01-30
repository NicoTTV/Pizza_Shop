class CommandeService {
    constructor(knex) {
        this.knex = knex;
    }

    async listerCommandes() {
        return await this.knex.select('*').from('commande');
    }

    changerEtatCommande(idCommande, etapeCommande) {
        this.knex
            .where({id: idCommande})
            .update({
                etape: etapeCommande
            });
    }
}
