<?php use Illuminate\Database\Eloquent\Model as Eloquent;

class Contenido extends Eloquent {
    use Illuminate\Database\Eloquent\SoftDeletingTrait;

    protected $table = 'contenidos';
    protected $dates = ['deleted_at'];
    protected $visible = ['id', 'titulo', 'contenible_id', 'contenible_type', 'puntos', 'created_at',
                          'link', 'resumen','orden','contenible'];
    protected $appends = ['link'];

    public function contenible() {
        return $this->morphTo();
    }

    public function autor() {
        return $this->belongsTo('Usuario');
    }

    public function getLinkAttribute() {
        $name = 'shw' . substr($this->attributes['contenible_type'], 0, 7);
        $attr = ['id' . substr($this->attributes['contenible_type'], 0, 3) => $this->attributes['contenible_id']];
        $app = Slim\Slim::getInstance();
        $stringcont = $app->urlFor($name, $attr);
        return 'https://www.santafe.gov.ar/atodacosta' . str_replace("/public/","/public/index.php/",$stringcont);
    }

    public function setTituloAttribute($value) {
        $this->attributes['titulo'] = $value;
        $this->attributes['huella'] = FilterFactory::calcHuella($value);
    }

    public function getIdentidadAttribute() {
        return $this->attributes['titulo'];
    }
}
