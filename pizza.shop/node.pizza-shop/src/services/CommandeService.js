export class CommandeService {
    constructor(knex) {
        this.knex = knex
    }

    async listerCommandes() {
        return await this.knex('commande').select('*')
    }

    async creerCommande(NewCommande) {
        const commande = NewCommande.commande
        await this.knex('commande').insert({
            id: commande.id,
            type_livraison: commande.type_livraison,
            montant_total: commande.montant,
            date_commande: commande.date_commande,
            mail_client: commande.mail_client,
        })
        for (const item of commande.items) {
            await this.knex('item').insert({
                numero: item.numero,
                taille: item.taille,
                quantite: item.quantite,
                libelle: item.libelle,
                libelle_taille: item.libelle_taille,
                tarif: item.tarif,
                commande_id: commande.id
            })
        }
    }

    changerEtatCommande(idCommande, etapeCommande) {
        this.knex
            .where({id: idCommande})
            .update({
                etape: etapeCommande
            });
    }
}
