@extends ('layouts.app')

@section('title', $localidad->nombre )

@section ('content')
<div class="container">
<div class="row">
  <div class="col-md-6">
    Información de la Localidad ({{ $localidad->codigo }}) 
    <b> {{ $localidad->nombre }} </b><br />
    @if ($aglomerado)
      del aglomerado <a href="{{ url("/aglo/{$aglomerado->id}") }}" >
      ({{ $aglomerado->codigo }}) 
      <b> {{ $aglomerado->nombre }} </b></a><br />
    @else
      NO está definido ningún aglomerado.
    @endif
    @if ($oDepto = $localidad->departamentos()->first())
      en el Departamento <a href="{{ url("/depto/{$oDepto->id}") }}" >
      ({{ $oDepto->codigo }}) 
      <b> {{ $oDepto->nombre }} </b></a><br />
      <a href="{{ url("/prov/{$oDepto->provincia->id}") }}" >
      ({{ $oDepto->provincia->codigo }}) 
      <b> {{ $oDepto->provincia->nombre }} </b></a>
    @else
      NO está definido ningún departamento.
    @endif
   </div>
   <div class="col-md-6 text-center">
    @auth
     @if ($carto)
        La base geográfica está cargada.
     @else
        NO está cargada la base geográfica.
     @endif 
     <br />
     @if ($listado)
        El Listado de viviendas esta cargado.
     @else
        NO está cargado el listado de viviendas.
     @endif 
     <div>
     @if ($carto && $listado)
       <button type="button" class="btn btn-primary" id="segmentar">Segmentar</button>
     @endif     
    @endauth
    </div>
</div>
</div>
</div>
 <div class="container">
   <!-- Modal -->
   <div class="modal fade" id="segmentaLocModal" role="dialog">
    <div class="modal-dialog">

     <!-- Modal content-->
     <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Segmentar Localidad</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal-body-segmenta">

      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
     </div>
    </div>
   </div>

<hr />
<div class="form-horizontal text-center">
    |<a href="{{ url('/localidad/'.$localidad->id.'/grafico') }}">
      Grafico de segmentación
    </a>|
    <!--a href="{{ url('/localidad/'.$localidad->id.'/pxseg') }}">
      PxSeg
    </a-->
<form action="/localidad/{{ $localidad->id }}" method="GET" enctype="multipart/form-data">
                @csrf
  <div class="form-groupi text-left">
    <label class="control-label" for="radio">Seleccione un Radio para ver:</label>
    <div class="">
<ul class="nav nav-center list-inline mx-auto justify-content-center">
  @foreach ($radios as $radio)
       @if ($radio->isSegmentado) 
          <li class="btn border border-success "> 
        @else
          <li class="btn "> 
        @endif 
    <a href="{{ url('/radio/'.$localidad->id.'/'.$radio->id) }}">
        {{ trim($radio->codigo) }}: {{ trim($radio->nombre) }} <br />Mzas: {{ trim($radio->CantMzas) }} 
        @if ($radio->isSegmentado) Segmentos: {{ trim($radio->isSegmentado) }} @endif
    </a>
    </li>
  @endforeach
</ul>
    </div>
  </div>
</form>
</div>


</div>
<div class='text-center'>
     @if($carto)
        {!! $svg !!}
     @endif
@if($localidad->codigo =='0125')         
</div>
<div>

<svg id="C30" class="mapa" xmlns="http://www.w3.org/2000/svg" height="500" width="450" viewBox="0 0 450 500">
   <path fill="#B698BE" clave_unica="18" tipo="Provincia" nombre="Corrientes" id="C30-18" class="provincia interactiva" codigo="18" d=" M 389 50 L 387 51 389 58 386 68 366 80 362 85 361 92 368 104 367 108 364 107 360 101 354 99 348 92 345 86 345 73 339 71 336 67 334 58 326 53 323 55 319 55 310 48 301 44 300 42 295 43 291 42 284 46 278 46 271 49 268 49 263 46 254 47 253 49 247 52 246 55 243 55 238 60 234 59 232 59 232 58 229 58 227 55 220 54 214 56 208 56 205 59 204 66 201 64 200 57 196 56 194 50 194 47 198 46 202 42 202 33 208 21 206 10 201 2 202 -0 450 -0 450 0 448 2 440 8 435 11 430 10 424 14 419 20 418 32 409 37 404 45 394 50 Z" stroke-width="1" style="z-index: 999;"></path>
   <path fill="#B698BE" clave_unica="06" tipo="Provincia" nombre="Buenos Aires" id="C30-06" class="provincia interactiva" codigo="06" d=" M 0 468 L 62 405 64 398 68 394 74 393 77 397 85 398 91 403 96 406 105 405 106 399 110 395 111 387 118 382 119 375 123 371 125 368 124 366 126 367 126 366 131 370 133 376 144 384 146 388 154 390 157 394 163 397 165 404 177 407 181 415 190 409 193 411 194 417 196 420 214 421 215 423 212 428 214 430 220 430 223 435 230 433 233 435 235 435 237 438 243 440 248 447 256 448 265 458 281 455 290 456 291 461 290 461 290 469 288 473 290 477 287 477 287 480 285 480 286 482 285 482 284 484 276 484 277 485 275 487 280 497 278 495 278 497 277 496 275 500 0 500 Z M 280 492 L 280 492 280 491 280 492 Z M 280 491 L 280 491 280 491 280 491 Z M 281 491 L 281 491 281 491 281 491 Z M 281 491 L 281 491 281 491 281 491 Z M 281 491 L 281 491 281 491 281 491 Z M 279 492 L 281 495 280 497 277 490 279 490 Z M 278 487 L 278 487 279 487 278 487 Z M 275 487 L 278 487 278 489 277 489 Z M 279 486 L 278 486 278 486 279 486 Z M 276 486 L 277 486 277 486 277 487 Z M 285 483 L 286 483 285 483 285 483 Z M 292 481 L 292 481 292 482 292 482 Z M 297 481 L 298 481 298 482 297 481 Z M 293 480 L 293 480 293 480 293 480 Z M 293 479 L 293 479 293 480 293 480 Z M 292 479 L 292 479 293 479 292 479 Z M 291 480 L 291 481 291 481 290 479 Z M 292 479 L 293 480 294 481 293 481 292 481 Z M 292 479 L 292 479 292 479 292 479 Z M 299 479 L 299 480 299 480 298 479 Z M 292 479 L 292 479 292 479 292 479 Z M 289 477 L 289 477 288 477 288 477 Z M 289 477 L 289 477 289 477 289 477 Z M 290 477 L 290 478 289 478 289 477 Z M 294 476 L 296 478 295 481 295 479 292 478 Z M 298 476 L 299 475 300 475 299 477 Z M 293 474 L 293 476 292 477 292 475 Z M 291 472 L 292 473 292 474 292 473 Z M 292 471 L 292 472 291 472 292 471 Z M 291 467 L 291 466 291 464 291 467 Z" stroke-width="1" style="z-index: 999;"></path>
   <path fill="#C6CEE1" clave_unica="82" tipo="Provincia" nombre="Santa Fe" id="C30-82" class="provincia interactiva" codigo="82" d=" M 45 423 L 0 468 0 -0 202 -0 201 2 206 10 208 21 202 33 202 42 198 46 193 48 196 56 194 66 197 70 198 80 196 86 199 93 198 96 192 101 189 110 171 133 164 138 161 149 153 155 152 162 147 171 137 174 136 180 129 181 129 187 116 196 111 197 107 196 100 201 99 206 96 209 97 212 97 219 91 225 91 228 94 230 96 236 91 245 94 253 93 262 88 264 89 270 88 277 83 283 85 289 84 295 88 303 86 309 89 320 91 326 95 331 97 338 105 351 112 353 116 356 121 357 123 360 122 363 126 367 125 366 124 366 125 368 119 375 118 382 111 387 110 395 106 399 105 405 96 406 91 403 85 398 77 397 74 393 69 394 64 398 62 405 Z" stroke-width="1" style="z-index: 999;"></path>
   <path fill="#BED3A8" clave_unica="30008" tipo="Departamento" nombre="Colón" id="C30-30008" class="departamento poligono" codigo="008" d=" M 338 215 L 334 223 325 226 322 228 323 233 325 235 327 243 326 249 322 259 328 270 326 279 319 283 318 280 316 281 311 277 307 270 299 267 300 261 290 260 287 266 286 262 279 259 279 248 275 242 276 237 273 235 274 223 277 224 275 227 285 229 283 227 286 223 290 225 292 223 291 220 294 217 299 221 303 220 302 212 307 213 307 211 315 206 320 213 327 215 329 213 334 213 Z" stroke-width="1" style="z-index: 999;"></path>
   <path fill="#DFEBD5" clave_unica="30015" tipo="Departamento" nombre="Concordia" id="C30-30015" class="departamento poligono" codigo="015" d=" M 305 152 L 296 147 292 138 293 137 296 137 302 135 301 131 305 126 305 123 307 122 309 117 313 115 326 118 323 121 325 129 325 136 323 137 323 138 345 149 349 159 353 161 353 164 347 171 346 178 336 185 337 188 343 193 345 198 338 215 331 212 325 215 320 213 316 208 315 206 317 205 318 199 314 199 316 192 313 192 314 190 312 190 312 188 314 186 307 184 303 179 303 174 306 173 307 171 302 161 308 159 310 156 Z" stroke-width="1"></path>
   <path fill="#D4E3C5" clave_unica="30021" tipo="Departamento" nombre="Diamante" id="C30-30021" class="departamento poligono" codigo="021" d=" M 91 225 L 97 220 98 211 105 208 108 213 109 213 108 217 110 218 110 221 118 225 122 223 125 224 126 229 125 227 125 229 123 230 125 231 124 234 125 238 128 239 130 235 133 236 136 246 139 244 143 248 139 251 139 254 129 255 124 262 119 261 116 262 114 266 118 275 116 277 112 277 112 285 110 288 107 289 105 288 107 287 105 285 103 284 105 286 104 293 99 297 92 296 90 301 87 300 85 297 85 289 83 283 88 277 89 270 88 264 93 262 94 253 91 245 96 236 94 230 91 228 Z" stroke-width="1"></path>
   <path fill="#D4E3C5" clave_unica="30028" tipo="Departamento" nombre="Federación" id="C30-30028" class="departamento poligono" codigo="028" d=" M 328 54 L 334 58 336 67 339 71 345 73 345 86 348 92 354 99 360 101 367 109 366 127 364 128 355 129 355 130 358 134 360 141 355 150 353 161 349 159 345 149 323 138 323 137 325 136 325 129 323 121 326 118 313 115 311 116 305 111 304 104 296 99 301 94 300 91 303 90 306 87 308 88 316 69 322 64 318 62 316 63 315 60 319 61 320 60 324 62 Z" stroke-width="1" style="z-index: 999;"></path>
   <path fill="#BED3A8" clave_unica="30035" tipo="Departamento" nombre="Federal" id="C30-30035" class="departamento poligono" codigo="035" d=" M 296 99 L 304 104 305 111 311 116 309 117 307 122 305 123 305 126 301 131 302 135 296 137 293 137 292 139 294 141 295 146 310 156 309 158 300 162 291 160 286 164 282 164 262 153 260 156 255 157 256 159 251 165 252 166 249 167 248 172 245 173 245 176 243 178 238 173 237 166 231 157 228 160 226 158 230 144 226 140 229 134 224 127 229 125 228 122 231 118 226 106 232 103 237 103 238 101 251 96 256 89 261 87 266 90 269 94 274 94 279 98 288 99 294 97 Z" stroke-width="1"></path>
   <path fill="#DFEBD5" clave_unica="30042" tipo="Departamento" nombre="Feliciano" id="C30-30042" class="departamento poligono" codigo="042" d=" M 250 50 L 253 49 254 47 259 46 263 46 268 49 275 48 278 46 284 46 291 42 295 43 300 42 301 44 310 48 319 55 323 55 326 53 328 54 324 62 320 60 319 61 315 60 316 63 318 62 322 64 316 69 308 88 306 87 303 90 300 91 301 94 296 99 294 97 288 99 279 98 274 94 270 94 266 90 261 87 258 88 263 84 260 82 260 78 254 77 251 74 252 66 250 63 243 62 240 58 243 55 246 55 247 52 Z" stroke-width="1" style="z-index: 999;"></path>
   <path fill="#D4E3C5" clave_unica="30049" tipo="Departamento" nombre="Gualeguay" id="C30-30049" class="departamento poligono" codigo="049" d=" M 174 296 L 176 295 179 298 186 299 189 300 189 293 202 294 207 299 206 303 231 314 230 317 233 317 232 319 231 320 232 323 229 325 230 327 227 330 229 334 224 336 223 342 221 342 220 346 216 349 215 352 217 354 216 355 217 356 212 362 213 363 209 362 211 365 208 364 209 367 207 367 204 367 202 365 201 366 195 366 193 363 190 362 181 373 184 376 181 378 182 379 190 382 191 388 199 391 204 401 208 401 209 407 213 411 215 419 219 423 220 430 213 429 212 428 215 422 213 421 201 421 196 420 194 417 193 411 190 409 181 415 177 407 165 404 163 397 157 394 154 390 146 388 144 384 133 376 130 369 122 363 123 360 122 357 128 361 129 365 131 362 133 365 136 365 135 363 138 362 143 366 146 365 144 364 146 363 147 369 153 365 154 369 157 369 158 363 159 368 162 369 168 366 165 361 171 355 167 353 166 348 165 348 164 351 160 344 157 342 156 344 154 340 153 342 152 340 149 341 146 338 152 338 153 335 149 336 149 334 151 331 155 330 161 332 164 322 169 318 172 311 172 304 171 302 Z" stroke-width="1"></path>
   <path fill="#BED3A8" clave_unica="30056" tipo="Departamento" nombre="Gualeguaychú" id="C30-30056" class="departamento poligono" codigo="056" d=" M 246 293 L 249 291 250 289 252 289 252 292 262 298 268 298 277 294 281 299 288 300 286 312 289 321 288 322 289 333 287 334 309 336 307 352 318 353 318 355 314 358 297 362 293 372 295 378 294 382 288 387 286 401 280 399 276 394 273 398 268 393 260 397 256 393 255 395 252 393 249 397 251 399 242 397 224 387 218 378 216 364 212 362 217 356 216 355 217 354 215 352 216 349 220 346 221 342 223 342 224 336 229 334 227 330 230 327 229 325 232 323 231 320 232 319 233 317 230 317 231 314 230 312 232 310 232 308 233 308 235 303 235 300 234 300 236 298 234 297 235 295 234 293 236 291 235 288 237 288 Z" stroke-width="1"></path>
   <path fill="#DFEBD5" clave_unica="30063" tipo="Departamento" nombre="Islas del Ibicuy" id="C30-30063" class="departamento poligono" codigo="063" d=" M 212 362 L 212 361 213 361 216 364 218 378 224 387 242 397 251 399 249 397 252 393 255 395 256 393 260 397 268 393 273 398 276 394 280 399 286 401 286 407 282 411 283 418 286 426 286 444 290 456 281 455 265 458 256 448 248 447 243 440 237 438 235 435 222 434 219 429 219 423 215 419 213 411 209 407 208 401 204 401 199 391 191 388 190 382 183 380 181 378 184 376 181 373 190 362 193 363 195 366 201 366 202 365 204 367 207 367 209 367 208 364 211 365 209 362 213 362 Z" stroke-width="1"></path>
   <path fill="#A8C690" clave_unica="30070" tipo="Departamento" nombre="La Paz" id="C30-30070" class="departamento poligono" codigo="070" d=" M 196 56 L 200 57 201 64 204 66 205 59 208 56 214 56 220 54 227 55 229 58 232 58 232 59 234 59 238 60 240 58 243 62 250 63 253 67 251 74 254 78 260 78 260 82 263 84 258 87 251 96 238 101 237 103 232 103 225 106 231 118 228 122 229 125 224 127 229 134 226 140 230 143 226 158 228 160 194 180 195 175 183 171 175 179 174 179 174 177 171 171 170 162 165 150 166 149 163 145 163 141 165 137 171 133 189 110 192 101 198 95 196 86 198 80 197 70 194 66 Z" stroke-width="1"></path>
   <path fill="#BED3A8" clave_unica="30077" tipo="Departamento" nombre="Nogoyá" id="C30-30077" class="departamento poligono" codigo="077" d=" M 193 219 L 200 226 197 228 199 230 204 229 208 231 212 228 219 227 217 229 215 236 204 248 205 250 202 263 203 265 201 270 205 273 202 280 204 283 204 290 202 290 202 294 189 293 189 300 186 299 179 298 176 295 174 296 174 292 158 287 153 284 154 280 156 278 147 265 144 264 143 260 141 258 138 259 139 258 138 256 139 254 139 251 143 248 139 244 136 246 133 236 140 233 144 229 154 235 155 229 162 227 162 224 164 223 164 219 179 225 Z" stroke-width="1"></path>
   <path fill="#DFEBD5" clave_unica="30084" tipo="Departamento" nombre="Paraná" id="C30-30084" class="departamento poligono" codigo="084" d=" M 163 145 L 166 149 165 150 174 179 176 179 183 171 195 175 194 180 199 177 197 192 199 193 203 193 205 195 204 197 202 201 195 207 196 211 194 213 192 220 179 225 164 219 164 223 162 224 162 227 155 229 154 235 144 229 140 233 133 236 130 235 128 239 127 238 126 239 124 234 125 231 123 230 125 229 125 227 126 229 125 224 122 223 119 225 114 224 115 223 110 221 110 218 108 217 109 213 108 213 105 208 97 212 96 208 99 206 100 201 107 196 111 197 116 196 129 187 129 181 136 180 137 174 147 171 152 162 153 155 160 151 Z" stroke-width="1"></path>
   <path fill="#A8C690" clave_unica="30088" tipo="Departamento" nombre="San Salvador" id="C30-30088" class="departamento poligono" codigo="088" d=" M 282 164 L 286 164 291 160 302 161 307 171 306 173 303 174 303 179 307 184 314 186 312 188 312 190 314 190 313 192 316 192 314 199 318 199 317 205 307 211 307 213 302 212 303 220 299 221 294 217 291 220 292 223 290 225 286 223 283 227 285 229 275 227 277 224 274 223 275 220 281 221 284 217 285 214 286 214 288 200 291 199 295 193 296 184 292 180 290 174 287 171 286 173 Z" stroke-width="1"></path>
   <path fill="#DFEBD5" clave_unica="30091" tipo="Departamento" nombre="Tala" id="C30-30091" class="departamento poligono" codigo="091" d=" M 219 227 L 225 227 234 235 241 236 242 242 242 244 238 246 241 249 238 250 240 256 236 262 239 263 239 270 237 272 238 273 236 274 238 275 237 279 236 280 237 282 234 285 236 287 236 291 234 293 235 295 234 297 236 298 234 300 235 300 235 303 233 308 232 308 232 310 230 313 206 303 207 299 202 294 204 287 202 280 205 273 201 270 203 265 202 263 205 250 204 248 215 236 217 229 Z" stroke-width="1"></path>
   <path fill="#A8C690" clave_unica="30098" tipo="Departamento" nombre="Uruguay" id="C30-30098" class="departamento poligono" codigo="098" d=" M 242 244 L 247 246 251 243 255 244 259 243 260 241 269 239 275 235 275 242 279 248 279 258 281 260 286 262 287 266 290 260 300 261 299 267 307 270 311 277 316 281 318 280 320 285 317 293 320 304 322 342 324 346 324 350 322 353 318 355 318 353 307 352 309 336 287 334 289 333 288 322 289 321 286 312 288 300 281 299 277 294 268 298 262 298 252 292 252 289 250 289 247 293 235 288 234 285 237 282 236 280 237 279 238 275 236 274 238 273 237 272 239 270 239 263 236 262 240 256 238 250 241 249 238 246 Z" stroke-width="1"></path>
   <path fill="#DFEBD5" clave_unica="30105" tipo="Departamento" nombre="Victoria" id="C30-30105" class="departamento poligono" codigo="105" d=" M 139 254 L 138 256 139 258 138 259 141 258 143 260 144 264 147 265 156 278 154 280 153 284 158 287 174 292 171 302 172 304 172 311 169 318 164 322 161 332 155 330 151 331 149 334 149 336 153 335 152 338 146 338 149 341 152 340 153 342 154 340 156 344 157 342 160 344 163 350 166 348 167 353 170 354 165 362 168 366 163 369 160 368 158 363 158 368 156 369 154 369 153 365 147 369 146 363 144 364 146 365 143 366 138 362 135 363 136 365 133 365 131 362 129 365 128 361 126 359 120 356 116 356 112 353 107 352 97 338 95 330 90 326 87 310 87 300 90 301 92 296 99 297 104 293 105 286 103 284 105 285 107 287 105 288 107 289 110 288 112 285 112 277 116 277 118 275 115 269 115 263 119 261 125 262 129 255 Z" stroke-width="1"></path>
   <path fill="#D4E3C5" clave_unica="30113" tipo="Departamento" nombre="Villaguay" id="C30-30113" class="departamento poligono" codigo="113" d=" M 263 153 L 282 164 286 173 287 171 290 174 292 180 296 184 295 193 291 199 288 200 286 214 285 214 284 217 281 221 275 220 272 230 273 236 269 239 260 241 259 243 255 244 248 244 247 246 242 244 241 236 234 235 225 227 212 228 208 231 205 229 199 230 197 229 200 226 193 219 193 217 196 211 195 207 202 201 205 195 203 193 199 193 197 192 199 177 231 157 237 166 238 173 244 178 246 174 245 173 245 173 248 172 247 170 249 170 248 169 249 167 251 167 251 165 256 159 255 157 Z" stroke-width="1"></path>
   <g clave_unica="30113080" tipo="Localidad cabecera de departamento/partido" nombre="VILLAGUAY" id="C30-30113080" class="localidad interactiva" codigo="080" style="display: none;"><circle r="3" cx="246" cy="222" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="246" cy="222" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30105060" tipo="Localidad cabecera de departamento/partido" nombre="VICTORIA" id="C30-30105060" class="localidad interactiva" codigo="060" style="display: none;"><circle r="3" cx="139" cy="297" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="139" cy="297" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30063060" tipo="Localidad cabecera de departamento/partido" nombre="VILLA PARANACITO" id="C30-30063060" class="localidad interactiva" codigo="060" style="display: none;"><circle r="3" cx="266" cy="423" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="266" cy="423" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30088020" tipo="Localidad cabecera de departamento/partido" nombre="SAN SALVADOR" id="C30-30088020" class="localidad interactiva" codigo="020" style="display: none;"><circle r="3" cx="296" cy="200" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="296" cy="200" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30091100" tipo="Localidad cabecera de departamento/partido" nombre="ROSARIO DEL TALA" id="C30-30091100" class="localidad interactiva" codigo="100" style="display: none;"><circle r="3" cx="233" cy="268" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="233" cy="268" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30042010" tipo="Localidad cabecera de departamento/partido" nombre="SAN JOSE DE FELICIANO" id="C30-30042010" class="localidad interactiva" codigo="010" style="display: none;"><circle r="3" cx="282" cy="65" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="282" cy="65" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30084160" tipo="Localidad capital de provincia" nombre="PARANA" id="C30-30084160" class="localidad interactiva" codigo="160" style="display: none;"><circle r="3.5" cx="110" cy="201" fill="white" stroke="black" stroke-width="1"></circle> 
	<circle r="1.5" cx="110" cy="201" fill="black"></circle> </g>
   <g clave_unica="30077070" tipo="Localidad cabecera de departamento/partido" nombre="NOGOYA" id="C30-30077070" class="localidad interactiva" codigo="070" style="display: none;"><circle r="3" cx="174" cy="275" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="174" cy="275" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30070040" tipo="Localidad cabecera de departamento/partido" nombre="LA PAZ" id="C30-30070040" class="localidad interactiva" codigo="040" style="display: none;"><circle r="3" cx="197" cy="98" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="197" cy="98" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30056070" tipo="Localidad cabecera de departamento/partido" nombre="GUALEGUAYCHU" id="C30-30056070" class="localidad interactiva" codigo="070" style="display: none;"><circle r="3" cx="285" cy="348" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="285" cy="348" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30049040" tipo="Localidad cabecera de departamento/partido" nombre="GUALEGUAY" id="C30-30049040" class="localidad interactiva" codigo="040" style="display: none;"><circle r="3" cx="211" cy="358" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="211" cy="358" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30015060" tipo="Localidad cabecera de departamento/partido" nombre="CONCORDIA" id="C30-30015060" class="localidad interactiva" codigo="060" style="display: none;"><circle r="3" cx="343" cy="177" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="343" cy="177" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30098040" tipo="Localidad cabecera de departamento/partido" nombre="CONCEPCION DEL URUGUAY" id="C30-30098040" class="localidad interactiva" codigo="040" style="display: none;"><circle r="3" cx="315" cy="294" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="315" cy="294" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30035030" tipo="Localidad cabecera de departamento/partido" nombre="FEDERAL" id="C30-30035030" class="localidad interactiva" codigo="030" style="display: none;"><circle r="3" cx="275" cy="125" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="275" cy="125" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30028070" tipo="Localidad cabecera de departamento/partido" nombre="FEDERACION" id="C30-30028070" class="localidad interactiva" codigo="070" style="display: none;"><circle r="3" cx="355" cy="135" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="355" cy="135" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30008020" tipo="Localidad cabecera de departamento/partido" nombre="COLON" id="C30-30008020" class="localidad interactiva" codigo="020" style="display: none;"><circle r="3" cx="325" cy="266" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="325" cy="266" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
   <g clave_unica="30021080" tipo="Localidad cabecera de departamento/partido" nombre="DIAMANTE" id="C30-30021080" class="localidad interactiva" codigo="080" style="display: none;"><circle r="3" cx="98" cy="236" fill="white" stroke="black" stroke-width="0.75"></circle> 
	<circle r="1.5" cx="98" cy="236" fill="white" stroke="black" stroke-width="0.75"></circle> </g>
<rect width="223.07390665657" height="26.5" x="27.5" y="458.33333333333" fill="rgba(255,255,255,0.75)"></rect>
<text text-anchor="middle" x="37.5" y="482.83333333333" font-size="10">0</text>
<text text-anchor="middle" x="230.57390665657" y="482.83333333333" font-size="10">200 km</text>
<rect width="193.07390665657" height="8" x="37.5" y="460.33333333333" stroke-width="1" fill="white" stroke="black"></rect>
<rect width="48.268476664143" height="4" x="37.5" y="464.33333333333" stroke-width="1" fill="black" stroke="black"></rect>
<rect width="48.268476664143" height="4" x="85.768476664143" y="460.33333333333" stroke-width="1" fill="black" stroke="black"></rect>
<rect width="96.536953328287" height="4" x="134.03695332829" y="464.33333333333" stroke-width="1" fill="black" stroke="black"></rect>
</svg>
</div>
@endif
@stop
@section('footer_scripts')
<script>
 $(document).ready( function () {
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
     });
     $("#segmentar").click(function () {
      // AJAX request
           $.ajax({
            url: "{{ url('localidad-segmenta') }}"+"/{{ $localidad->id }}",
            type: 'post',
            data: {id: {{ $localidad->id }},format: 'html'},
            success: function(response){ 
              // Add response in Modal body
              $('#modal-body-segmenta').html(response);
              // Display Modal
              $('#segmentaLocModal').modal('show'); 
            }
           });
     });
 });
</script>
@endsection
