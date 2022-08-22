from __future__ import annotations
import os
from jose import jwt, JWTError
from fastapi import HTTPException, status, Cookie
from api.utils.token import decodeJWT

SECRET_KEY = os.environ["JWT_SECRET_KEY"]
ALGORITHM = os.environ["JWT_ALGORITHM"]


async def ensure_admin(access_token: str = Cookie()):
    # print({"Token": access_token})
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Invalid token please login once again",
        headers={"Authorization": "Bearer"},
    )
    if not access_token:
        raise credentials_exception
    token_type = access_token.split(' ')[0]
    if token_type != "Bearer":
        raise credentials_exception
    token = access_token.split(' ')[1]
    try:
        # payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        payload = decodeJWT(token)
        if not payload:
            raise credentials_exception
        name: str = payload.get("name")  # letter use email for this
        if name is None:
            raise credentials_exception
        # token_data = TokenData(name=name)
        return payload
    except JWTError:
        raise credentials_exception