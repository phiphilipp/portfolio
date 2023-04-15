const axios = require('axios');
const mongoose = require('mongoose');
import {LightModel} from "@/models/light";

  
// Verbindung zur Datenbank herstellen mit neuem UrlParser
mongoose.connect(process.env.MONGODB_URI, { useNewUrlParser: true, useUnifiedTopology: true });
const db = mongoose.connection;
// Error Handling für die Datenbank-Verbindung
db.on('error', console.error.bind(console, 'Verbindungsfehler:'));
// Fügt einen One-Time Listener zu Testzwecken hinzu, der nach Ausführung gelöscht wird
db.once('open', function() {
  console.log('Verbindung zur Datenbank hergestellt.');
});

// Asynchrone Funktion zum Speichern der Daten in der Datenbank
async function saveData(req, res ) {
  try {
    // Daten von der API abrufen
    const response = await axios.get(`http://${process.env.PHILIPS_BRIDGE}/api/${process.env.PHILIPS_USER}/lights`);
    const data = response.data;

    // Erzeugen eines light Models nach Aufbau des LightModel
    const light = new LightModel({
      lightId: data["1"].uniqueid,
      on: data["1"].state.on,
    });

    // Erzeugen der update Konstante für den Fall des updateMany()
    const update = {$set: {on: data["1"].state.on}};

    // Daten in der Datenbank speichern
    // Falls der Datensatz bereits besteht, dann wird dieser aktualisiert
    if(db.collection("lightmodels").findOne(light) != null){
      await LightModel.updateMany({lightId: data["1"].uniqueid}, update );
    }
    // Erstellen eines neuen Datensatzes, wenn kein Datensatz vorhanden
    else{
      await LightModel.insertMany(light);
    }
    // Senden des Statuscodes
    console.log('Daten erfolgreich gespeichert.');
    res.send(201);
  } 
  // Falls ein Fehler aufgetreten ist, Antwort mit Statuscode
  catch (error) {
    console.error(error);
    res.send(400);
  }
}

export default saveData;


