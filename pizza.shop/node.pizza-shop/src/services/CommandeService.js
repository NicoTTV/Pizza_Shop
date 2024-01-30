export class CommandeService {
    constructor(knex) {
        this.knex = knex;
    }

    async listerCommandes() {
        return await this.knex('commande').select('*');
    }
}
