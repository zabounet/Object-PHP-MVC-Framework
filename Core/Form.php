<?php
namespace App\Core;

class Form{

    private $formCode = '';

    public function create(): string{
        return $this->formCode;
    }

    // Valide si tous les champs du formulaire sont remplis
    public static function validate(array $form, array $champsObligatoires, array $champsFacultatifs = []): bool{

        // On parcourt les champs
        foreach($champsObligatoires as $champObligatoire){
            // Si le champ est absent ou vide dans le formulaire
            if(!isset($form[$champObligatoire]) || empty($form[$champObligatoire])){
                return false;
            } 
        }
        // Si le paramètre $champsFacultatif n'est pas vide
        foreach($champsFacultatifs as $champFacultatif){
                // Si les champs sont remplis et non vide
                if(isset($form[$champFacultatif]) && !empty($form[$champFacultatif])){
                    //
                }
            }
        return true;
    }

    
    static function Argon2IDOptions(): array
    {
        $options = [
            'memory_cost' => 64 * 1024,   // 64MB memory cost
            'time_cost' => 4,             // 4 iterations
            'threads' => 2,               // 2 threads
        ];
        return $options;
    }

    public function ajoutAttributs(array $attributs): string{

        // On initialise une chaîne de caractères 
        $str = '';

        // On liste les attributs "courts"
        $courts = ["checked","disabled", "readonly", 
                   "multiple", "required", "autofocus", 
                   "autocomplete", "novalidate", "formnovalidate",];

        // On boucle sur le tableau d'attributs
        foreach($attributs as $attribut => $valeur){
            // Si l'attribut est dans la liste attributs courts
            if(in_array($attribut, $courts) && $valeur == true){
                $str .= " $attribut";
            }else{
                // On ajoute attribut="valeur"
                $str .= " $attribut='$valeur'";
            }
        }

        return $str;
    }

    // Création de la balise d'ouverture du formulaire avec son action, sa methode et éventuels attributs
    public function debutForm(string $methode = "post", string $action = "#", array $attributs = []): self{
           
        $this->formCode .= "<form action='$action' method='$methode'";

        // On ajoute les attributs éventuels
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs).'>' : '>';

        return $this;
    }

    public function finForm(): self{
        
        // Création d'un token d'authentification unique
        $token = sha1(uniqid());
        $this->formCode .= "<input type='hidden' name='token' value='$token'>";
        $this->formCode .= "</form>";
        $_SESSION['token'] = $token;
        return $this;
    }

    public function labelFor(string $for, string $texte, array $attributs = []): self{

        // On ouvre la balise
        $this->formCode .= "<label for='$for'";

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) . '>' : '>';

        // On ajoute le texte
        $this->formCode .= $texte;

        return $this;
    }

    public function closeLabel(): self{

        $this->formCode .= '</label>';

        return $this;
    }

    public function ajoutInput(string $type, string $name, array $attributs = []): self{

        // On ouvre la balise
        $this->formCode .= "<input type='$type' name='$name'";

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) . ">" : ">";

        return $this;
    }

    public function ajoutTextarea(string $name, string $valeur = '', array $attributs = []): self{

        // On ouvre la balise
        $this->formCode .= "<textarea name='$name'";
        
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) . ">" : ">";

        $this->formCode .= $valeur ."</textarea>";

        return $this;
    }

    public function ajoutSelect(string $name, array $options, array $attributs = []): self{
        
        // On crée le select
        $this->formCode .= "<select name='$name'";

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->ajoutAttributs($attributs) . ">" : ">";

        // On ajoute les options
        foreach($options as $valeur => $texte){
            $this->formCode .= "<option value='$valeur'>". $texte . "</option>";
        }

        // On ferme le select
        $this->formCode .= '</select>';
        
        return $this;
    }
}