export class CommandeService {
    constructor(knex) {
        this.knex = knex;
    }

    async listerCommandes() {
        return await this.knex('commande').select('*');
    }

    changerEtatCommande(idCommande, etapeCommande) {
        this.knex
            .where({id: idCommande})
            .update({
                etape: etapeCommande
            });
    }
}
