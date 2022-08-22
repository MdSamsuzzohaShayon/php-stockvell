from fastapi import APIRouter, status, HTTPException, Depends, Response
from datetime import datetime
from passlib.hash import pbkdf2_sha256
from api.utils.token import create_access_token
from api.dependencies.auth import ensure_admin
from api.models.AdminModel import AdminIn_Pydantic, Admin_Pydantic, AdminTable




router = APIRouter()


@router.post("/signup", status_code=status.HTTP_201_CREATED)
async def admin_add(admin: AdminIn_Pydantic):
    admin_body = dict(admin)
    admin_body["role"] = "SUPER"
    admin_body["password"] = pbkdf2_sha256.hash(admin.password)
    new_admin = await AdminTable.create(**admin_body)
    admin_obj = await Admin_Pydantic.from_tortoise_orm(new_admin)
    admin_res = dict(admin_obj)
    del admin_res["password"]
    return admin_res



"""
@router.get("/all", response_model=list[AdminGetSchema])
async def admin_all(payload: dict = Depends(ensure_admin)):
    # eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoic2hheW9uIiwicm9sZSI6IlNVUEVSIiwiZXhwIjoxNjYxMTg3ODE4fQ.NAatZOHeqrnjDaY0kjqxvbI1-Ydk20kQOy8DvRQttJM
    # print({"payload": payload})
    query = Admin.select()
    all_admin = await database.fetch_all(query=query)
    return all_admin


# Get some more fields
# formData: OAuth2PasswordRequestForm = Depends()
@router.post("/login")
async def admin_login(response: Response, admin: AdminLoginSchema):
    query = Admin.select().where(Admin.c.name == admin.name)
    found_admin = await database.fetch_one(query=query)

    # print(found_admin.__dict__)

    if not found_admin:
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="No user found")

    if not pbkdf2_sha256.verify(admin.password, found_admin.password):
        raise HTTPException(status_code=status.HTTP_404_NOT_FOUND, detail="Invalid password")

    # We need to install python-jose to generate and verify the JWT tokens in Python:
    access_token = create_access_token(
        data={
            'name': found_admin.name,
            'role': found_admin.role,
        },
        expire_in_minutes= 60 * 24 # 1 day
    )

    # Starlette provides a set_cookie method to allow you to set cookies on the response object.
    response.set_cookie(
        key="access_token",
        value=f"Bearer {access_token}",
        httponly=True,
        expires=60 * 60 * 24 # 1 day
    )

    return {"msg": "logged in successfully - (got cookie)"}


# logout
@router.get("/logout")
async def admin_logout(response: Response):
    response.set_cookie(
        key="access_token",
        value="",
        httponly=True,
        expires=0  # 1 day
    )
    return {"msg": "logged out successfully - (no cookie)"}

"""
