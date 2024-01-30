import express from 'express';
import knex from 'knex';
import { CommandeService } from './services/CommandeService.js';
import {CommandesAction} from "./actions/CommandesAction.js";

const app = express();
const port = process.env.PORT || 3000;

const knexConfig = {
    client: 'mysql',
    connection: {
        host: 'node-db.pizza-shop',
        user: 'node',
        password: 'node',
        database: 'node.pizza_shop'
    }
};

const knexInstance = knex(knexConfig);

app.get('/', (req, res) =>
    res.send('Hello World!'));
app.get('/error', (req, res) => {
    throw new Error('error');
});
app.get('/commandes', (req, res) => {
    const commandeService = new CommandeService(knexInstance);
    const listerCommandesAction = new CommandesAction(commandeService);
    listerCommandesAction.execute(req, res).then(r => console.log(r)).catch(e => console.log(e));

});

app.patch('/commande/:id/etape', (req, res) => {
    const commandeService = new CommandeService(knexInstance);
    const etapeCommande = new EtapeCommandeAction(commandeService);
});

app.listen(port, () =>
    console.log(`app listening on port ${port}!`
    )
);