import express from 'express';
import knex from 'knex';

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


});

app.listen(port, () =>
    console.log(`app listening on port ${port}!`
    )
);