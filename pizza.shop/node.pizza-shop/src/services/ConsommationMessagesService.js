import amqp from "amqplib"

export class ConsommationMessagesService {
    constructor(commandeService) {
        this.commandeService = commandeService
        this.queueName = 'nouvelles_commandes'
    }

    async connecter() {
        try {
            // Remplacez 'votre_utilisateur' et 'votre_mot_de_passe' par les vraies valeurs
            const url = 'amqp://commande:commande@rabbitmq.pizza-shop';

            const connection = await amqp.connect(url, { clientProperties: { connection_name: 'commande' } });
            return await connection.createChannel();
        } catch (err) {
            console.log('Erreur lors de la connexion à AMQP:', err);
            throw err; // Propager l'erreur pour une gestion plus appropriée en dehors de cette fonction
        }
    }


    async execute() {
        try {
            const channel = await this.connecter()
            await channel.assertQueue(this.queueName)
            channel.consume(this.queueName, (msg) => {
                if (msg !== null) {
                    const donneesCommande = JSON.parse(msg.content)
                    this.commandeService.creerCommande(donneesCommande)
                        .then(() => {
                            console.log('Commande créée avec succès :' + msg.content)
                        })
                        .catch(err => {
                            console.error('Erreur lors de la création de la commande:', err)
                        })
                    channel.ack(msg)
                }
            })
        } catch (err) {
            console.log(err)
        }
    }
}