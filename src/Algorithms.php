<?php

namespace Vudev\JsonWebToken;

interface Algorithms {
    const HS256 = ['hash_hmac', 'SHA256'];
    const HS384 = ['hash_hmac', 'SHA384'];
    const HS512 = ['hash_hmac', 'SHA512'];
    const RS256 = ['openssl', 'SHA256', OPENSSL_ALGO_SHA256];
    const RS384 = ['openssl', 'SHA384', OPENSSL_ALGO_SHA384];
    const RS512 = ['openssl', 'SHA512', OPENSSL_ALGO_SHA512];
}