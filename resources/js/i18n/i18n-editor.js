export default () => {
  return {


    messages: {
      /**
       * Other below: translation of different UI components of the editor.js core
       */
      ui: {
        "blockTunes": {
          "toggler": {
            "Click to tune": "Klicken Sie, um die Einstellung vorzunehmen",
            "or drag to move": "oder ziehen Sie, um es zu verschieben"
          },
        },
        "inlineToolbar": {
          "converter": {
            "Convert to": "Konvertieren zu"
          }
        },
        "toolbar": {
          "toolbox": {
            "Add": "Hinzufügen"
          }
        }
      },

      /**
       * Section for translation Tool Names: both block and inline tools
       */
      toolNames: {
        "Text": "Text",
        "Heading": "Überschrift",
        "List": "List",
        "Warning": "Warnung",
        "Checklist": "Checkliste",
        "Quote": "Zitieren",
        "Code": "Code",
        "Delimiter": "Trennzeichen",
        "Raw HTML": "Raw HTML",
        "Table": "Table",
        "Link": "Link",
        "Marker": "Marker",
        "Bold": "Bold",
        "Italic": "Kursiv",
        "InlineCode": "Inline-Code",
      },

      /**
       * Section for passing translations to the external tools classes
       */
      tools: {
        /**
         * Each subsection is the i18n dictionary that will be passed to the corresponded plugin
         * The name of a plugin should be equal the name you specify in the 'tool' section for that plugin
         */
        "warning": { // <-- 'Warning' tool will accept this dictionary section
          "Title": "Titel",
          "Message": "Nachricht",
        },

        /**
         * Link is the internal Inline Tool
         */
        "link": {
          "Add a link": "Link hinzufügen"
        },
        /**
         * The "stub" is an internal block tool, used to fit blocks that does not have the corresponded plugin
         */
        "stub": {
          'The block can not be displayed correctly.': 'Der Block kann nicht korrekt angezeigt werden.'
        }
      },

      /**
       * Section allows to translate Block Tunes
       */
      blockTunes: {
        /**
         * Each subsection is the i18n dictionary that will be passed to the corresponded Block Tune plugin
         * The name of a plugin should be equal the name you specify in the 'tunes' section for that plugin
         *
         * Also, there are few internal block tunes: "delete", "moveUp" and "moveDown"
         */

        "delete": {
          "Delete": "Löschen"
        },
        "moveUp": {
          "Move up": "oben bewegen"
        },
        "moveDown": {
          "Move down": "unten bewegen"
        }
      },
    }
  
  // Add translations for other languages if needed
  };
};
