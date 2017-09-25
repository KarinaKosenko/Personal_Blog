<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace M;

/**
 * Description of smiles
 *
 * @author admin
 */
class Smiles {
    use \Core\Traits\Singleton;
		
    public function smile($var)
    {
        $symbol = array(':mellow:',
                                        '<_<',
                                        ':)',
                                        ':wub:',
                                        ':angry:',
                                        ':(',
                                        ':unsure:',
                                        ':wacko:',
                                        ':blink:',
                                        '-_-',
                                        ':rolleyes:',
                                        ':huh:',
                                        '^_^',
                                        ':o',
                                        ';)',
                                        ':P',
                                        ':D',
                                        ':lol:',
                                        'B)',
                                        ':ph34r:');
        $graph = array('<img src="/images/smiles/mellow.png">',
                        '<img src="/images/smiles/dry.png">',
                        '<img src="/images/smiles/smile.png">',
                        '<img src="/images/smiles/wub.png">',
                        '<img src="/images/smiles/angry.png">',
                        '<img src="/images/smiles/sad.png">',
                        '<img src="/images/smiles/unsure.png">',
                        '<img src="/images/smiles/wacko.png">',
                        '<img src="/images/smiles/blink.png">',
                        '<img src="/images/smiles/sleep.png">',
                        '<img src="/images/smiles/rolleyes.gif">',
                        '<img src="/images/smiles/huh.png">',
                        '<img src="/images/smiles/happy.png">',
                        '<img src="/images/smiles/ohmy.png">',
                        '<img src="/images/smiles/wink.png">',
                        '<img src="/images/smiles/tongue.png">',
                        '<img src="/images/smiles/biggrin.png">',
                        '<img src="/images/smiles/laugh.png">',
                        '<img src="/images/smiles/cool.png">',
                        '<img src="/images/smiles/ph34r.png">');
        return str_replace($symbol, $graph, $var);
    }

}
