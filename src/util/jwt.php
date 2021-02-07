<?php
namespace util;

class jwt
{
    public const EXPIRATION_SECONDS = 15 * 60 + 120;
    public const HASH_ALG = 'SHA256';
    
    public static function create($uid, $jwt_key)
    {
        if (!$uid || !$jwt_key)
            return false;
        
        $header = [ 'alg' => 'SHA256',
                    'typ' => 'JWT' ];

        if (!$base64url_header = static::encode_base64url($header))
            return false;
        
        $issued_at = strtotime('now');       
        $payload = [ 'iss' => 'projom.se',
                     'sub' => $uid,
                     'iat' => $issued_at,
                     'exp' => $issued_at + static::EXPIRATION_SECONDS ];

        if (!$base64url_payload = static::encode_base64url($payload))
            return false;

        $signature = static::generate_signature(static::HASH_ALG, $base64url_header, $base64url_payload, $jwt_key);        
        if (!$signature)
            return false;

        $token = sprintf('%s.%s.%s', $base64url_header, $base64url_payload, $signature);
        if (!static::validate_token_structure($token))
            return false;
        return $token;
    }

    public static function encode_base64url(array $data)
    {
        if (!$data)
            throw new \Exception('Missing data', 500);
        $json_string = \util\json::encode($data);        
        return \util\base64::encode_url($json_string);
    }

    public static function decode_base64url(string $base64url)
    {
        if (!$base64url)
            throw new \Exception('Missing base64url', 500);        
        $json_string = \util\base64::decode_url($base64url);
        return \util\json::decode($json_string);
    }
    
    public static function generate_signature($alg, $base64url_header, $base64url_payload, $jwt_key)
    {
        $data = sprintf('%s.%s', $base64url_header, $base64url_payload);
        return hash_hmac($alg, $data, $jwt_key);        
    }

    public static function validate_token_structure($token)
    {
        $parts = explode('.', $token);
        if (count($parts) != 3)
            return false;
            
        if (!$parts[0] || !$parts[1] || !$parts[2])
            return false;
            
        return $parts;
    }    
    
    public static function validate(string $token)
    {
        if (!$parts = static::validate_token_structure($token))
            return false;

        $base64url_header = $parts[0];
        $header = static::decode_base64url($base64url_header);
        $base64url_payload = $parts[1];
        $payload = static::decode_base64url($base64url_payload);

        $time = strtotime('now');
        if (!isset($payload['exp']) || $time >= $payload['exp'])
            throw new \Exception('Token expired', 401);        

        if (!isset($header['alg']) || $header['alg'] != static::HASH_ALG)
            return false;
        if (!isset($payload['sub']) || !validate_id($payload['sub']))
            return false;

        if (!$record = table('User')->select('JWTKey')->where(['UserID' => $payload['sub']])->query())
            return false;
        
        $known_signature = static::generate_signature(static::HASH_ALG, $base64url_header, $base64url_payload, $record['JWTKey']);                        
        return hash_equals($known_signature, $parts[2]);        
    }    

    public static function create_key()
    {
        return \util\base64::encode(random_bytes(30));
    }
}
