from __future__ import annotations
import os
from datetime import datetime, timedelta
from jose import jwt
import time


SECRET_KEY = os.environ["JWT_SECRET_KEY"]
ALGORITHM = os.environ["JWT_ALGORITHM"]


def create_access_token(data: dict, expire_in_minutes):
    to_encode = data.copy()
    expire = datetime.utcnow() + timedelta(minutes=expire_in_minutes)
    to_encode.update({"exp": expire})
    encoded_jwt = jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)
    return encoded_jwt


def decodeJWT(token: str):
    try:
        decode_token = jwt.decode(token, SECRET_KEY, algorithms=ALGORITHM)
        # print(decode_token['exp'])
        # print(time.time())
        if decode_token['exp'] >= time.time():
            return decode_token
        return None
    except:
        return None



