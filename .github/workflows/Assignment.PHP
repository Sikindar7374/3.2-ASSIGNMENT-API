const express = require('express');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.json());

// Connect to MongoDB using Mongoose
mongoose.connect('mongodb://localhost/my_database', { useNewUrlParser: true });

// Define a schema for the player data
const playerSchema = new mongoose.Schema({
  name: { type: String, required: true },
  age: { type: Number, required: true },
  position: { type: String, required: true },
  team: { type: String, required: true }
});

// Create a model for the player data
const Player = mongoose.model('Player', playerSchema);

// API endpoints for adding, updating, and deleting players
app.post('/players', async (req, res) => {
  const player = new Player(req.body);
  await player.save();
  res.send(player);
});

app.put('/players/:id', async (req, res) => {
  const { id } = req.params;
  const player = await Player.findByIdAndUpdate(id, req.body, { new: true });
  res.send(player);
});

app.delete('/players/:id', async (req, res) => {
  const { id } = req.params;
  const player = await Player.findByIdAndDelete(id);
  res.send(player);
});

// API endpoints for performing queries
app.get('/players', async (req, res) => {
  const players = await Player.find();
  res.send(players);
});

app.get('/players/:id', async (req, res) => {
  const { id } = req.params;
  const player = await Player.findById(id);
  res.send(player);
});

app.get('/players/count', async (req, res) => {
  const count = await Player.count();
  res.send({ count });
});

app.post('/players/query', async (req, res) => {
  const players = await Player.find(req.body);
  res.send(players);
});

// Start the server
app.listen(3000, () => {
  console.log('Server started on port 3000');
});
