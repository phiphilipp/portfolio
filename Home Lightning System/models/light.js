const mongoose = require("mongoose");

// Schema für die Lampen-Daten erstellen
const dataSchema = new mongoose.Schema({
  lightId: {
    type: String,
    /*  Setzen der lightId auf unique, um sicherzustellen, 
        dass jede Lampe nur einmalig vorhanden ist. */
    unique: true,  
  },
  // Wert für den An/Aus Status der Lampe
  on: Boolean
});

// Erzeugen des mongoose-Models und exportieren des Models als Modul
export const LightModel = mongoose.model('LightModel', dataSchema);
